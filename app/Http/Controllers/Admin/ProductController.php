<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Enums\StockMovementType;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::with(['category', 'variants', 'media'])
            ->when($request->search, fn ($q, $s) => $q->where('sku', 'like', "%{$s}%")->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$s}%"]))
            ->when($request->category_id, fn ($q, $c) => $q->where('category_id', $c))
            ->when($request->has('status') && $request->status !== '', fn ($q) => $q->where('is_active', $request->boolean('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => Category::active()->get(),
            'filters' => $request->only(['search', 'category_id', 'status']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::active()->get(),
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|array',
            'variants.*.sku' => 'required|string|distinct',
            'variants.*.stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::create($request->only([
                'name', 'description', 'short_description', 'slug', 'sku',
                'price', 'compare_price', 'cost_price', 'category_id',
                'is_active', 'is_featured', 'weight', 'meta',
            ]));

            foreach ($request->variants as $variantData) {
                $variant = $product->variants()->create($variantData);

                if (($variantData['stock'] ?? 0) > 0) {
                    $variant->stockMovements()->create([
                        'quantity' => $variantData['stock'],
                        'type' => StockMovementType::PURCHASE->value,
                        'notes' => 'Initial stock',
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->addMedia($image)->toMediaCollection('images');
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product): Response
    {
        $product->load(['category', 'variants.stockMovements', 'media']);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product): Response
    {
        $product->load(['category', 'variants', 'media']);

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => Category::active()->get(),
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'slug' => "required|string|unique:products,slug,{$product->id}",
            'sku' => "required|string|unique:products,sku,{$product->id}",
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->update($request->only([
                'name', 'description', 'short_description', 'slug', 'sku',
                'price', 'compare_price', 'cost_price', 'category_id',
                'is_active', 'is_featured', 'weight', 'meta',
            ]));

            if ($request->has('variants')) {
                $existingIds = [];
                foreach ($request->variants as $variantData) {
                    if (isset($variantData['id'])) {
                        $product->variants()->where('id', $variantData['id'])->update($variantData);
                        $existingIds[] = $variantData['id'];
                    } else {
                        $variant = $product->variants()->create($variantData);
                        $existingIds[] = $variant->id;
                    }
                }
                $product->variants()->whereNotIn('id', $existingIds)->delete();
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->addMedia($image)->toMediaCollection('images');
                }
            }

            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $mediaId) {
                    $product->media()->where('id', $mediaId)->first()?->delete();
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}

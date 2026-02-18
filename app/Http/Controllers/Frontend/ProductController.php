<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::active()
            ->with(['media', 'variants', 'category'])
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($q2) => $q2->where('slug', $c)))
            ->when($request->search, fn ($q, $s) => $q->whereRaw("JSON_EXTRACT(name, '$.".app()->getLocale()."') LIKE ?", ["%{$s}%"]))
            ->when($request->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'newest' || !$request->sort, fn ($q) => $q->latest())
            ->when($request->min_price, fn ($q, $p) => $q->where('price', '>=', $p))
            ->when($request->max_price, fn ($q, $p) => $q->where('price', '<=', $p))
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Frontend/Products/Index', [
            'products' => $products,
            'categories' => Category::active()->withCount('products')->orderBy('sort_order')->get(),
            'filters' => $request->only(['search', 'category', 'sort', 'min_price', 'max_price']),
        ]);
    }

    public function show(Product $product): Response
    {
        $product->load(['category', 'variants', 'media']);

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('media')
            ->take(4)
            ->get();

        return Inertia::render('Frontend/Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}

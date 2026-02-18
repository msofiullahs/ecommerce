<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->when($request->search, fn ($q, $s) => $q->whereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$s}%"]))
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Categories/Create', [
            'parentCategories' => Category::root()->active()->get(),
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->only(['name', 'description', 'slug', 'parent_id', 'sort_order', 'is_active']));

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('Categories/Edit', [
            'category' => $category,
            'parentCategories' => Category::root()->active()->where('id', '!=', $category->id)->get(),
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'slug' => "required|string|unique:categories,slug,{$category->id}",
        ]);

        $category->update($request->only(['name', 'description', 'slug', 'parent_id', 'sort_order', 'is_active']));

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}

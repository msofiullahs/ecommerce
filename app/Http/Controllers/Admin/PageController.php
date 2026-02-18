<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Pages/Index', [
            'pages' => Page::orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Pages/Create', [
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug',
            'content' => 'required|array',
        ]);

        Page::create($request->all());

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page): Response
    {
        return Inertia::render('Pages/Edit', [
            'page' => $page,
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'slug' => "required|string|unique:pages,slug,{$page->id}",
            'content' => 'required|array',
        ]);

        $page->update($request->all());

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}

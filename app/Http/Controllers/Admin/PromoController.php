<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PromoController extends Controller
{
    public function index(Request $request): Response
    {
        $promos = Promo::when($request->search, fn ($q, $s) => $q->where('code', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Promos/Index', [
            'promos' => $promos,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Promos/Create', [
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|unique:promos,code',
            'name' => 'required|array',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        Promo::create($request->all());

        return redirect()->route('admin.promos.index')->with('success', 'Promo created successfully.');
    }

    public function edit(Promo $promo): Response
    {
        return Inertia::render('Promos/Edit', [
            'promo' => $promo,
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function update(Request $request, Promo $promo): RedirectResponse
    {
        $request->validate([
            'code' => "required|string|unique:promos,code,{$promo->id}",
            'name' => 'required|array',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
        ]);

        $promo->update($request->all());

        return redirect()->route('admin.promos.index')->with('success', 'Promo updated successfully.');
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();
        return redirect()->route('admin.promos.index')->with('success', 'Promo deleted successfully.');
    }
}

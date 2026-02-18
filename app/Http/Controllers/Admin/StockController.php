<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StockController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function index(Request $request): Response
    {
        $variants = ProductVariant::with('product')
            ->when($request->filter === 'low', fn ($q) => $q->whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0))
            ->when($request->filter === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->when($request->search, fn ($q, $s) => $q->where('sku', 'like', "%{$s}%"))
            ->where('is_active', true)
            ->orderBy('stock')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Stock/Index', [
            'variants' => $variants,
            'filters' => $request->only(['search', 'filter']),
            'lowStockCount' => ProductVariant::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0)->where('is_active', true)->count(),
            'outOfStockCount' => ProductVariant::where('stock', '<=', 0)->where('is_active', true)->count(),
        ]);
    }

    public function movements(Request $request): Response
    {
        $movements = StockMovement::with(['productVariant.product', 'user'])
            ->when($request->search, fn ($q, $s) => $q->whereHas('productVariant', fn ($q2) => $q2->where('sku', 'like', "%{$s}%")))
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->when($request->from, fn ($q, $f) => $q->whereDate('created_at', '>=', $f))
            ->when($request->to, fn ($q, $t) => $q->whereDate('created_at', '<=', $t))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Stock/Movements', [
            'movements' => $movements,
            'filters' => $request->only(['search', 'type', 'from', 'to']),
            'types' => collect(StockMovementType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]),
        ]);
    }

    public function adjust(Request $request): RedirectResponse
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|not_in:0',
            'type' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $variant = ProductVariant::findOrFail($request->product_variant_id);
        $type = StockMovementType::from($request->type);

        $this->stockService->adjustStock($variant, $request->quantity, $type, null, $request->notes);

        return back()->with('success', 'Stock adjusted successfully.');
    }
}

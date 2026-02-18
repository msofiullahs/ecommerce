<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Events\StockLow;
use App\Exceptions\InsufficientStockException;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function adjustStock(
        ProductVariant $variant,
        int $quantity,
        StockMovementType $type,
        ?string $reference = null,
        ?string $notes = null,
    ): StockMovement {
        return DB::transaction(function () use ($variant, $quantity, $type, $reference, $notes) {
            $movement = $variant->stockMovements()->create([
                'quantity' => $quantity,
                'type' => $type->value,
                'reference' => $reference,
                'notes' => $notes,
                'user_id' => auth()->id(),
            ]);

            $variant->increment('stock', $quantity);

            if ($variant->fresh()->isLowStock()) {
                event(new StockLow($variant));
            }

            return $movement;
        });
    }

    public function deductForOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $variant = $item->productVariant;

                if ($variant->stock < $item->quantity) {
                    throw new InsufficientStockException($variant);
                }

                $this->adjustStock(
                    $variant,
                    -$item->quantity,
                    StockMovementType::SALE,
                    $order->order_number,
                );
            }
        }
    }

    public function returnForOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $this->adjustStock(
                    $item->productVariant,
                    $item->quantity,
                    StockMovementType::RETURN,
                    $order->order_number,
                    'Order cancelled - stock returned',
                );
            }
        }
    }

    public function getLowStockVariants(int $limit = 50): Collection
    {
        return ProductVariant::with('product')
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit($limit)
            ->get();
    }
}

<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class OrderService
{
    public function __construct(
        private StockService $stockService,
        private PromoService $promoService,
    ) {}

    public function createFromCart(Cart $cart, array $shippingData, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($cart, $shippingData, $paymentMethod) {
            $cart->load('items.product', 'items.productVariant');

            $subtotal = $this->calculateSubtotal($cart);
            $discount = 0;
            $promo = null;

            if ($cart->promo_code) {
                try {
                    $promo = $this->promoService->validate($cart->promo_code, $subtotal, $cart->user);
                    $discount = $promo->calculateDiscount($subtotal);
                } catch (InvalidArgumentException) {
                    // Invalid promo - proceed without discount
                }
            }

            $total = max(0, $subtotal - $discount);

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $cart->user_id,
                'status' => OrderStatus::PENDING,
                'payment_status' => 'unpaid',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => 0,
                'tax_amount' => 0,
                'total' => $total,
                'promo_code' => $cart->promo_code,
                ...$shippingData,
            ]);

            foreach ($cart->items as $item) {
                $price = $item->productVariant?->effective_price ?? $item->product->price;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->productVariant?->name,
                    'sku' => $item->productVariant?->sku ?? $item->product->sku,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity,
                ]);
            }

            // Deduct stock
            $order->load('items.productVariant');
            $this->stockService->deductForOrder($order);

            // Apply promo
            if ($promo) {
                $this->promoService->apply($promo, $order);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->update(['promo_code' => null]);

            event(new OrderPlaced($order));

            return $order;
        });
    }

    public function updateStatus(Order $order, OrderStatus $newStatus): void
    {
        $currentStatus = $order->status;

        if (!$currentStatus->canTransitionTo($newStatus)) {
            throw new InvalidArgumentException(
                "Cannot transition from {$currentStatus->value} to {$newStatus->value}"
            );
        }

        $order->update(['status' => $newStatus]);

        match ($newStatus) {
            OrderStatus::SHIPPED => $order->update(['shipped_at' => now()]),
            OrderStatus::DELIVERED => $order->update(['delivered_at' => now()]),
            OrderStatus::CANCELLED => $this->stockService->returnForOrder($order),
            default => null,
        };

        event(new OrderStatusChanged($order, $currentStatus, $newStatus));
    }

    private function calculateSubtotal(Cart $cart): float
    {
        return $cart->items->sum(function ($item) {
            $price = $item->productVariant?->effective_price ?? $item->product->price;
            return $price * $item->quantity;
        });
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}

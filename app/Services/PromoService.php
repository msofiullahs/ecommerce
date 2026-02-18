<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Promo;
use App\Models\User;
use InvalidArgumentException;

class PromoService
{
    public function validate(string $code, float $subtotal, ?User $user = null): Promo
    {
        $promo = Promo::where('code', $code)->first();

        if (!$promo) {
            throw new InvalidArgumentException(__('promos.not_found'));
        }

        if (!$promo->isValid()) {
            throw new InvalidArgumentException(__('promos.invalid'));
        }

        if ($promo->minimum_order !== null && $subtotal < $promo->minimum_order) {
            throw new InvalidArgumentException(__('promos.minimum_order', ['amount' => $promo->minimum_order]));
        }

        if ($user && $promo->hasUserExceededLimit($user)) {
            throw new InvalidArgumentException(__('promos.usage_exceeded'));
        }

        return $promo;
    }

    public function apply(Promo $promo, Order $order): void
    {
        $promo->increment('usage_count');

        if ($order->user_id) {
            $promo->users()->attach($order->user_id, ['order_id' => $order->id]);
        }
    }

    public function calculateDiscount(string $code, float $subtotal, ?User $user = null): float
    {
        $promo = $this->validate($code, $subtotal, $user);
        return $promo->calculateDiscount($subtotal);
    }
}

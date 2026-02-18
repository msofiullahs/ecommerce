<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'promo_code' => $this->promo_code,
            'items' => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'product' => [
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'image' => $item->product->getFirstMediaUrl('images', 'thumb'),
                ],
                'variant' => $item->productVariant ? [
                    'name' => $item->productVariant->name,
                    'price' => $item->productVariant->price,
                    'sku' => $item->productVariant->sku,
                ] : null,
                'quantity' => $item->quantity,
            ]),
            'subtotal' => $this->subtotal,
        ];
    }
}

<?php

namespace App\Exceptions;

use App\Models\ProductVariant;
use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public function __construct(public readonly ProductVariant $variant)
    {
        parent::__construct("Insufficient stock for variant: {$variant->sku}. Available: {$variant->stock}");
    }
}

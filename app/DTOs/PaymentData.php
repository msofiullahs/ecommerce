<?php

namespace App\DTOs;

class PaymentData
{
    public function __construct(
        public readonly bool $success,
        public readonly string $reference,
        public readonly string $status,
        public readonly ?string $redirectUrl = null,
        public readonly ?string $snapToken = null,
        public readonly ?string $checkoutUrl = null,
        public readonly array $metadata = [],
    ) {}
}

<?php

namespace App\Contracts;

use App\DTOs\PaymentData;
use App\Models\Order;

interface PaymentGatewayInterface
{
    public function createPayment(Order $order): PaymentData;
    public function verifyPayment(string $reference): PaymentData;
    public function refund(Order $order, float $amount): PaymentData;
    public function getConfigFields(): array;
    public function getName(): string;
}

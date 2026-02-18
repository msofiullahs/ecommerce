<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\PaymentFailedException;
use App\Models\PaymentGateway;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class PaymentManager
{
    private array $gateways = [
        'stripe' => StripeGateway::class,
        'midtrans' => MidtransGateway::class,
        'doku' => DokuGateway::class,
    ];

    public function gateway(string $name): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$name])) {
            throw new InvalidArgumentException("Payment gateway [{$name}] not supported.");
        }

        $config = PaymentGateway::where('name', $name)->where('is_active', true)->first();

        if (!$config) {
            throw new PaymentFailedException("Payment gateway [{$name}] is not configured or inactive.");
        }

        return new $this->gateways[$name]($config->config ?? []);
    }

    public function getActiveGateways(): Collection
    {
        return PaymentGateway::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function getAllGateways(): Collection
    {
        return PaymentGateway::orderBy('sort_order')->get();
    }
}

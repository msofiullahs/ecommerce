<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentData;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class DokuGateway implements PaymentGatewayInterface
{
    private string $baseUrl;

    public function __construct(private array $config)
    {
        $this->baseUrl = ($this->config['is_sandbox'] ?? true)
            ? 'https://api-sandbox.doku.com'
            : 'https://api.doku.com';
    }

    public function createPayment(Order $order): PaymentData
    {
        $requestId = uniqid('doku-', true);

        $body = [
            'order' => [
                'amount' => (int) $order->total,
                'invoice_number' => $order->order_number,
            ],
            'payment' => [
                'payment_due_date' => 60,
            ],
            'customer' => [
                'name' => $order->shipping_name,
                'email' => $order->user?->email ?? '',
            ],
        ];

        $response = Http::withHeaders([
            'Client-Id' => $this->config['client_id'] ?? '',
            'Request-Id' => $requestId,
            'Request-Timestamp' => now()->toIso8601String(),
        ])->post("{$this->baseUrl}/checkout/v1/payment", $body);

        $data = $response->json();

        return new PaymentData(
            success: $response->successful(),
            reference: $data['order']['invoice_number'] ?? $order->order_number,
            status: 'pending',
            checkoutUrl: $data['response']['payment']['url'] ?? null,
        );
    }

    public function verifyPayment(string $reference): PaymentData
    {
        $response = Http::withHeaders([
            'Client-Id' => $this->config['client_id'] ?? '',
        ])->get("{$this->baseUrl}/orders/v1/status/{$reference}");

        $data = $response->json();
        $status = $data['transaction']['status'] ?? 'UNKNOWN';

        return new PaymentData(
            success: $status === 'SUCCESS',
            reference: $reference,
            status: $status,
        );
    }

    public function refund(Order $order, float $amount): PaymentData
    {
        return new PaymentData(
            success: false,
            reference: $order->order_number,
            status: 'refund_not_implemented',
        );
    }

    public function getConfigFields(): array
    {
        return [
            ['key' => 'client_id', 'label' => 'Client ID', 'type' => 'text'],
            ['key' => 'shared_key', 'label' => 'Shared Key / Secret Key', 'type' => 'password'],
            ['key' => 'is_sandbox', 'label' => 'Sandbox Mode', 'type' => 'boolean'],
        ];
    }

    public function getName(): string
    {
        return 'DOKU';
    }
}

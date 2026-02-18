<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentData;
use App\Models\Order;

class MidtransGateway implements PaymentGatewayInterface
{
    public function __construct(private array $config)
    {
        \Midtrans\Config::$serverKey = $this->config['server_key'] ?? '';
        \Midtrans\Config::$isProduction = !($this->config['is_sandbox'] ?? true);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function createPayment(Order $order): PaymentData
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->shipping_name,
                'email' => $order->user?->email ?? '',
                'phone' => $order->shipping_phone,
            ],
            'item_details' => $order->items->map(fn ($item) => [
                'id' => $item->sku,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product_name, 0, 50),
            ])->toArray(),
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return new PaymentData(
            success: true,
            reference: $order->order_number,
            status: 'pending',
            snapToken: $snapToken,
        );
    }

    public function verifyPayment(string $reference): PaymentData
    {
        $status = \Midtrans\Transaction::status($reference);

        $success = in_array($status->transaction_status, ['capture', 'settlement']);

        return new PaymentData(
            success: $success,
            reference: $reference,
            status: $status->transaction_status,
        );
    }

    public function refund(Order $order, float $amount): PaymentData
    {
        $params = ['amount' => (int) $amount, 'reason' => 'Order refund'];
        $refund = \Midtrans\Transaction::refund($order->order_number, $params);

        return new PaymentData(
            success: $refund->status_code === '200',
            reference: $order->order_number,
            status: $refund->transaction_status ?? 'refund',
        );
    }

    public function getConfigFields(): array
    {
        return [
            ['key' => 'server_key', 'label' => 'Server Key', 'type' => 'password'],
            ['key' => 'client_key', 'label' => 'Client Key', 'type' => 'text'],
            ['key' => 'is_sandbox', 'label' => 'Sandbox Mode', 'type' => 'boolean'],
        ];
    }

    public function getName(): string
    {
        return 'Midtrans';
    }
}

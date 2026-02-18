<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentData;
use App\Models\Order;
use Stripe\StripeClient;

class StripeGateway implements PaymentGatewayInterface
{
    private StripeClient $stripe;

    public function __construct(private array $config)
    {
        $this->stripe = new StripeClient($this->config['secret_key'] ?? '');
    }

    public function createPayment(Order $order): PaymentData
    {
        $lineItems = $order->items->map(fn ($item) => [
            'price_data' => [
                'currency' => strtolower(config('ecommerce.currency', 'idr')),
                'product_data' => [
                    'name' => $item->product_name . ($item->variant_name ? " - {$item->variant_name}" : ''),
                ],
                'unit_amount' => (int) ($item->price * 100),
            ],
            'quantity' => $item->quantity,
        ])->toArray();

        $session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('orders.success', $order) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.show'),
            'metadata' => ['order_id' => $order->id, 'order_number' => $order->order_number],
        ]);

        return new PaymentData(
            success: true,
            reference: $session->id,
            status: 'pending',
            redirectUrl: $session->url,
        );
    }

    public function verifyPayment(string $reference): PaymentData
    {
        $session = $this->stripe->checkout->sessions->retrieve($reference);

        return new PaymentData(
            success: $session->payment_status === 'paid',
            reference: $reference,
            status: $session->payment_status,
        );
    }

    public function refund(Order $order, float $amount): PaymentData
    {
        $refund = $this->stripe->refunds->create([
            'payment_intent' => $order->payment_reference,
            'amount' => (int) ($amount * 100),
        ]);

        return new PaymentData(
            success: $refund->status === 'succeeded',
            reference: $refund->id,
            status: $refund->status,
        );
    }

    public function getConfigFields(): array
    {
        return [
            ['key' => 'publishable_key', 'label' => 'Publishable Key', 'type' => 'text'],
            ['key' => 'secret_key', 'label' => 'Secret Key', 'type' => 'password'],
            ['key' => 'webhook_secret', 'label' => 'Webhook Secret', 'type' => 'password'],
        ];
    }

    public function getName(): string
    {
        return 'Stripe';
    }
}

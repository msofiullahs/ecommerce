<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function stripe(Request $request, PaymentManager $manager): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $gateway = $manager->gateway('stripe');
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret', '')
            );

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $order = Order::find($session->metadata->order_id);

                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_reference' => $session->payment_intent,
                        'paid_at' => now(),
                    ]);
                }
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function midtrans(Request $request): JsonResponse
    {
        $notification = $request->all();
        $orderNumber = $notification['order_id'] ?? null;

        if (!$orderNumber) {
            return response()->json(['error' => 'Missing order_id'], 400);
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus = $notification['fraud_status'] ?? '';

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || empty($fraudStatus)) {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['payment_status' => 'failed']);
        }

        return response()->json(['status' => 'ok']);
    }

    public function doku(Request $request): JsonResponse
    {
        $data = $request->all();
        $invoiceNumber = $data['order']['invoice_number'] ?? null;

        if (!$invoiceNumber) {
            return response()->json(['error' => 'Missing invoice_number'], 400);
        }

        $order = Order::where('order_number', $invoiceNumber)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $status = $data['transaction']['status'] ?? '';

        if ($status === 'SUCCESS') {
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        } elseif ($status === 'FAILED') {
            $order->update(['payment_status' => 'failed']);
        }

        return response()->json(['status' => 'ok']);
    }
}

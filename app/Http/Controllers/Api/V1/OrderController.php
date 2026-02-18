<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderResource;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()->orders()->latest()->paginate(10);
        return OrderResource::collection($orders);
    }

    public function show(Request $request, int $orderId): OrderResource
    {
        $order = $request->user()->orders()->with('items.product.media')->findOrFail($orderId);
        return new OrderResource($order);
    }

    public function store(Request $request, OrderService $orderService, PaymentManager $paymentManager): JsonResponse
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_province' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:10',
            'payment_method' => 'required|string',
        ]);

        $cart = app(CartService::class)->getOrCreateCart($request->user(), null);

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        try {
            $order = $orderService->createFromCart($cart, $request->only([
                'shipping_name', 'shipping_phone', 'shipping_address',
                'shipping_city', 'shipping_province', 'shipping_postal_code', 'notes',
            ]), $request->payment_method);

            $paymentData = null;
            if ($request->payment_method !== 'cod') {
                $payment = $paymentManager->gateway($request->payment_method)->createPayment($order);
                $order->update(['payment_reference' => $payment->reference, 'payment_gateway' => $request->payment_method]);
                $paymentData = [
                    'redirect_url' => $payment->redirectUrl,
                    'snap_token' => $payment->snapToken,
                    'checkout_url' => $payment->checkoutUrl,
                ];
            }

            return response()->json([
                'order' => new OrderResource($order),
                'payment' => $paymentData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

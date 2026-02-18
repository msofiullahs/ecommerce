<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function show(Request $request, CartService $cartService, PaymentManager $paymentManager): Response
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $cart->load('items.product.media', 'items.productVariant');

        return Inertia::render('Frontend/Checkout', [
            'cart' => $cart,
            'cartItems' => $cart->items,
            'paymentGateways' => $paymentManager->getActiveGateways(),
            'user' => $request->user(),
        ]);
    }

    public function process(Request $request, OrderService $orderService, PaymentManager $paymentManager): RedirectResponse
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

        $cart = app(CartService::class)->getOrCreateCart($request->user(), $request->session()->getId());

        if ($cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            $order = $orderService->createFromCart($cart, $request->only([
                'shipping_name', 'shipping_phone', 'shipping_address',
                'shipping_city', 'shipping_province', 'shipping_postal_code', 'notes',
            ]), $request->payment_method);

            // Process payment
            if ($request->payment_method !== 'cod') {
                $payment = $paymentManager->gateway($request->payment_method)->createPayment($order);
                $order->update(['payment_reference' => $payment->reference, 'payment_gateway' => $request->payment_method]);

                if ($payment->redirectUrl) {
                    return Inertia::location($payment->redirectUrl);
                }
            }

            return redirect()->route('orders.success', $order);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Order $order): Response
    {
        $order->load('items');

        return Inertia::render('Frontend/OrderSuccess', [
            'order' => $order,
        ]);
    }
}

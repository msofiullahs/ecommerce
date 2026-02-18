<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Services\CartService;
use App\Services\PromoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function show(Request $request): Response
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->user(),
            $request->session()->getId()
        );

        $cart->load('items.product.media', 'items.productVariant');

        return Inertia::render('Frontend/Cart', [
            'cart' => $cart,
            'cartItems' => $cart->items,
        ]);
    }

    public function addItem(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->getOrCreateCart(
            $request->user(),
            $request->session()->getId()
        );

        $this->cartService->addItem(
            $cart,
            $request->product_id,
            $request->product_variant_id,
            $request->quantity
        );

        return back()->with('success', 'Item added to cart.');
    }

    public function updateItem(Request $request, CartItem $cartItem): RedirectResponse
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        $this->cartService->updateItemQuantity($cartItem, $request->quantity);

        return back()->with('success', 'Cart updated.');
    }

    public function removeItem(CartItem $cartItem): RedirectResponse
    {
        $this->cartService->removeItem($cartItem);
        return back()->with('success', 'Item removed from cart.');
    }

    public function applyPromo(Request $request, PromoService $promoService): RedirectResponse
    {
        $request->validate(['code' => 'required|string']);

        $cart = $this->cartService->getOrCreateCart(
            $request->user(),
            $request->session()->getId()
        );

        try {
            $promoService->validate($request->code, $cart->subtotal, $request->user());
            $cart->update(['promo_code' => $request->code]);
            return back()->with('success', 'Promo code applied.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CartResource;
use App\Models\CartItem;
use App\Services\CartService;
use App\Services\PromoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function show(Request $request): CartResource
    {
        $cart = $this->cartService->getOrCreateCart($request->user(), null);
        $cart->load('items.product.media', 'items.productVariant');

        return new CartResource($cart);
    }

    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->getOrCreateCart($request->user(), null);

        $this->cartService->addItem(
            $cart, $request->product_id, $request->product_variant_id, $request->quantity
        );

        return response()->json(['message' => 'Item added to cart'], 201);
    }

    public function updateItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $this->cartService->updateItemQuantity($cartItem, $request->quantity);

        return response()->json(['message' => 'Cart updated']);
    }

    public function removeItem(CartItem $cartItem): JsonResponse
    {
        $this->cartService->removeItem($cartItem);
        return response()->json(['message' => 'Item removed']);
    }

    public function applyPromo(Request $request, PromoService $promoService): JsonResponse
    {
        $request->validate(['code' => 'required|string']);
        $cart = $this->cartService->getOrCreateCart($request->user(), null);

        try {
            $promoService->validate($request->code, $cart->subtotal, $request->user());
            $cart->update(['promo_code' => $request->code]);
            return response()->json(['message' => 'Promo applied']);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

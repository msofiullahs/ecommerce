import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useCart() {
    const addToCart = (productId: number, variantId?: number, quantity: number = 1) => {
        router.post(route('cart.add'), {
            product_id: productId,
            product_variant_id: variantId,
            quantity,
        }, { preserveScroll: true });
    };

    const updateQuantity = (itemId: number, quantity: number) => {
        router.patch(route('cart.update', { cartItem: itemId }), {
            quantity,
        }, { preserveScroll: true });
    };

    const removeItem = (itemId: number) => {
        router.delete(route('cart.remove', { cartItem: itemId }), {
            preserveScroll: true,
        });
    };

    const applyPromo = (code: string) => {
        router.post(route('cart.promo'), { code }, { preserveScroll: true });
    };

    return { addToCart, updateQuantity, removeItem, applyPromo };
}

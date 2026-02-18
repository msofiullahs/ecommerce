<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface CartItem {
    id: number;
    product: {
        id: number;
        name: string;
        slug: string;
        image_url: string;
    };
    variant: {
        id: number;
        name: string;
    } | null;
    price: number;
    quantity: number;
    subtotal: number;
}

interface Cart {
    items: CartItem[];
    subtotal: number;
    discount: number;
    total: number;
    promo_code: string | null;
}

const props = defineProps<{
    cart: Cart;
}>();

const promoCode = ref(props.cart.promo_code || '');

const promoForm = useForm({
    promo_code: '',
});

function updateQuantity(item: CartItem, newQuantity: number) {
    if (newQuantity < 1) return;
    router.put(route('cart.update', item.id), {
        quantity: newQuantity,
    }, {
        preserveScroll: true,
    });
}

function removeItem(item: CartItem) {
    router.delete(route('cart.remove', item.id), {
        preserveScroll: true,
    });
}

function applyPromo() {
    promoForm.promo_code = promoCode.value;
    promoForm.post(route('cart.apply-promo'), {
        preserveScroll: true,
    });
}

function formatCurrency(value: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
}
</script>

<template>
    <Head :title="t('cart.title')" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">{{ t('cart.title') }}</h1>

        <div v-if="cart.items.length > 0" class="lg:flex lg:gap-8">
            <!-- Cart Items -->
            <div class="flex-1">
                <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ t('cart.product') }}
                                </th>
                                <th class="hidden px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:table-cell">
                                    {{ t('cart.price') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ t('cart.quantity') }}
                                </th>
                                <th class="hidden px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:table-cell">
                                    {{ t('cart.subtotal') }}
                                </th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="item in cart.items" :key="item.id">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <img
                                            :src="item.product.image_url"
                                            :alt="item.product.name"
                                            class="h-16 w-16 shrink-0 rounded-md object-cover"
                                        />
                                        <div>
                                            <Link
                                                :href="route('products.show', item.product.slug)"
                                                class="font-medium text-gray-900 hover:text-indigo-600"
                                            >
                                                {{ item.product.name }}
                                            </Link>
                                            <p v-if="item.variant" class="mt-1 text-sm text-gray-500">
                                                {{ item.variant.name }}
                                            </p>
                                            <p class="mt-1 text-sm font-medium text-gray-900 sm:hidden">
                                                {{ formatCurrency(item.price) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden px-6 py-4 text-sm text-gray-900 sm:table-cell">
                                    {{ formatCurrency(item.price) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            @click="updateQuantity(item, item.quantity - 1)"
                                            class="flex h-8 w-8 items-center justify-center rounded border text-gray-600 hover:bg-gray-100"
                                        >
                                            -
                                        </button>
                                        <input
                                            :value="item.quantity"
                                            @change="updateQuantity(item, Number(($event.target as HTMLInputElement).value))"
                                            type="number"
                                            min="1"
                                            class="h-8 w-14 rounded border-gray-300 text-center text-sm"
                                        />
                                        <button
                                            @click="updateQuantity(item, item.quantity + 1)"
                                            class="flex h-8 w-8 items-center justify-center rounded border text-gray-600 hover:bg-gray-100"
                                        >
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="hidden px-6 py-4 text-sm font-medium text-gray-900 sm:table-cell">
                                    {{ formatCurrency(item.subtotal) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        @click="removeItem(item)"
                                        class="text-red-500 transition hover:text-red-700"
                                        :title="t('cart.remove')"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="mt-8 lg:mt-0 lg:w-80">
                <div class="rounded-lg border bg-white p-6 shadow-sm">
                    <!-- Promo Code -->
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            {{ t('cart.promo_code') }}
                        </label>
                        <div class="flex gap-2">
                            <input
                                v-model="promoCode"
                                type="text"
                                :placeholder="t('cart.promo_placeholder')"
                                class="flex-1 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <button
                                @click="applyPromo"
                                :disabled="promoForm.processing"
                                class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700 disabled:opacity-50"
                            >
                                {{ t('cart.apply') }}
                            </button>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 border-t pt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ t('cart.subtotal') }}</span>
                            <span class="font-medium text-gray-900">{{ formatCurrency(cart.subtotal) }}</span>
                        </div>
                        <div v-if="cart.discount > 0" class="flex items-center justify-between text-sm">
                            <span class="text-green-600">{{ t('cart.discount') }}</span>
                            <span class="font-medium text-green-600">-{{ formatCurrency(cart.discount) }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t pt-3">
                            <span class="text-lg font-semibold text-gray-900">{{ t('cart.total') }}</span>
                            <span class="text-lg font-bold text-indigo-600">{{ formatCurrency(cart.total) }}</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <Link
                        :href="route('checkout.show')"
                        class="mt-6 block w-full rounded-lg bg-indigo-600 px-6 py-3 text-center text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        {{ t('cart.checkout') }}
                    </Link>
                </div>
            </div>
        </div>

        <!-- Empty Cart -->
        <div v-else class="rounded-lg border bg-white py-16 text-center">
            <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
            </svg>
            <p class="mb-4 text-lg text-gray-500">{{ t('cart.empty') }}</p>
            <Link
                :href="route('products.index')"
                class="inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
                {{ t('cart.continue_shopping') }}
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface CartItem {
    id: number;
    product: {
        id: number;
        name: string;
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
}

interface PaymentGateway {
    id: number;
    name: string;
    code: string;
    description: string | null;
    logo_url: string | null;
}

const props = defineProps<{
    cart: Cart;
    paymentGateways: PaymentGateway[];
}>();

const form = useForm({
    name: '',
    phone: '',
    address: '',
    city: '',
    province: '',
    postal_code: '',
    payment_gateway_id: props.paymentGateways.length > 0 ? props.paymentGateways[0].id : null,
    notes: '',
});

function placeOrder() {
    form.post(route('checkout.process'));
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
    <Head :title="t('checkout.title')" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">{{ t('checkout.title') }}</h1>

        <form @submit.prevent="placeOrder">
            <div class="lg:flex lg:gap-8">
                <!-- Shipping Form -->
                <div class="flex-1">
                    <div class="rounded-lg border bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-semibold text-gray-900">
                            {{ t('checkout.shipping_info') }}
                        </h2>

                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">
                                    {{ t('checkout.name') }}
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">
                                    {{ t('checkout.phone') }}
                                </label>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :class="{ 'border-red-500': form.errors.phone }"
                                />
                                <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.phone }}
                                </p>
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">
                                    {{ t('checkout.address') }}
                                </label>
                                <textarea
                                    v-model="form.address"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :class="{ 'border-red-500': form.errors.address }"
                                />
                                <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.address }}
                                </p>
                            </div>

                            <!-- City + Province -->
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">
                                        {{ t('checkout.city') }}
                                    </label>
                                    <input
                                        v-model="form.city"
                                        type="text"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.city }"
                                    />
                                    <p v-if="form.errors.city" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.city }}
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">
                                        {{ t('checkout.province') }}
                                    </label>
                                    <input
                                        v-model="form.province"
                                        type="text"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.province }"
                                    />
                                    <p v-if="form.errors.province" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.province }}
                                    </p>
                                </div>
                            </div>

                            <!-- Postal Code -->
                            <div class="sm:w-1/2">
                                <label class="mb-1 block text-sm font-medium text-gray-700">
                                    {{ t('checkout.postal_code') }}
                                </label>
                                <input
                                    v-model="form.postal_code"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :class="{ 'border-red-500': form.errors.postal_code }"
                                />
                                <p v-if="form.errors.postal_code" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.postal_code }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mt-6 rounded-lg border bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-semibold text-gray-900">
                            {{ t('checkout.payment_method') }}
                        </h2>

                        <div class="space-y-3">
                            <label
                                v-for="gateway in paymentGateways"
                                :key="gateway.id"
                                class="flex cursor-pointer items-center gap-4 rounded-lg border p-4 transition hover:border-indigo-300"
                                :class="{
                                    'border-indigo-600 bg-indigo-50': form.payment_gateway_id === gateway.id,
                                    'border-gray-200': form.payment_gateway_id !== gateway.id,
                                }"
                            >
                                <input
                                    v-model="form.payment_gateway_id"
                                    type="radio"
                                    :value="gateway.id"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                />
                                <img
                                    v-if="gateway.logo_url"
                                    :src="gateway.logo_url"
                                    :alt="gateway.name"
                                    class="h-8 w-auto"
                                />
                                <div>
                                    <p class="font-medium text-gray-900">{{ gateway.name }}</p>
                                    <p v-if="gateway.description" class="text-sm text-gray-500">
                                        {{ gateway.description }}
                                    </p>
                                </div>
                            </label>
                        </div>
                        <p v-if="form.errors.payment_gateway_id" class="mt-2 text-sm text-red-600">
                            {{ form.errors.payment_gateway_id }}
                        </p>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="mt-8 lg:mt-0 lg:w-96">
                    <div class="sticky top-24 rounded-lg border bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-semibold text-gray-900">
                            {{ t('checkout.order_summary') }}
                        </h2>

                        <div class="space-y-4">
                            <div
                                v-for="item in cart.items"
                                :key="item.id"
                                class="flex items-center gap-4"
                            >
                                <img
                                    :src="item.product.image_url"
                                    :alt="item.product.name"
                                    class="h-12 w-12 shrink-0 rounded-md object-cover"
                                />
                                <div class="flex-1 text-sm">
                                    <p class="font-medium text-gray-900">{{ item.product.name }}</p>
                                    <p v-if="item.variant" class="text-gray-500">{{ item.variant.name }}</p>
                                    <p class="text-gray-500">x{{ item.quantity }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ formatCurrency(item.subtotal) }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3 border-t pt-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ t('cart.subtotal') }}</span>
                                <span class="font-medium">{{ formatCurrency(cart.subtotal) }}</span>
                            </div>
                            <div v-if="cart.discount > 0" class="flex items-center justify-between text-sm">
                                <span class="text-green-600">{{ t('cart.discount') }}</span>
                                <span class="font-medium text-green-600">-{{ formatCurrency(cart.discount) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ t('checkout.shipping') }}</span>
                                <span class="font-medium text-green-600">{{ t('checkout.free') }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t pt-3">
                                <span class="text-lg font-semibold">{{ t('checkout.total') }}</span>
                                <span class="text-lg font-bold text-indigo-600">{{ formatCurrency(cart.total) }}</span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="mt-6 w-full rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span v-if="form.processing">{{ t('common.loading') }}</span>
                            <span v-else>{{ t('checkout.place_order') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

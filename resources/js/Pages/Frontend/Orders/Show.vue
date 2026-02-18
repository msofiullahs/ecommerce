<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface OrderItem {
    id: number;
    product_name: string;
    variant_name: string | null;
    price: number;
    quantity: number;
    subtotal: number;
}

interface Order {
    id: number;
    order_number: string;
    status: string;
    total: number;
    subtotal: number;
    discount: number;
    created_at: string;
    items: OrderItem[];
    shipping_name: string;
    shipping_phone: string;
    shipping_address: string;
    shipping_city: string;
    shipping_province: string;
    shipping_postal_code: string;
    payment_method: string;
    payment_status: string;
}

defineProps<{
    order: Order;
}>();

function formatCurrency(value: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
}

function formatDate(dateStr: string): string {
    return new Date(dateStr).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function statusColor(status: string): string {
    const colors: Record<string, string> = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        shipped: 'bg-purple-100 text-purple-800',
        delivered: 'bg-green-100 text-green-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function paymentStatusColor(status: string): string {
    const colors: Record<string, string> = {
        paid: 'bg-green-100 text-green-800',
        pending: 'bg-yellow-100 text-yellow-800',
        failed: 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}
</script>

<template>
    <Head :title="`${t('orders.order_number')}${order.order_number}`" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <Link :href="route('orders.index')" class="mb-2 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ t('common.back') }}
                </Link>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ t('orders.order_number') }}{{ order.order_number }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">{{ formatDate(order.created_at) }}</p>
            </div>
            <span :class="statusColor(order.status)" class="inline-flex rounded-full px-4 py-2 text-sm font-semibold">
                {{ order.status }}
            </span>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="rounded-lg border bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ t('orders.items') }}</h2>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ t('orders.product') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ t('orders.price') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ t('orders.quantity') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ t('orders.subtotal') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="item in order.items" :key="item.id">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ item.product_name }}</p>
                                    <p v-if="item.variant_name" class="text-sm text-gray-500">
                                        {{ item.variant_name }}
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ formatCurrency(item.price) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ item.quantity }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ formatCurrency(item.subtotal) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="border-t px-6 py-4">
                        <div class="flex justify-end">
                            <div class="w-64 space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ t('orders.subtotal') }}</span>
                                    <span class="font-medium">{{ formatCurrency(order.subtotal) }}</span>
                                </div>
                                <div v-if="order.discount > 0" class="flex items-center justify-between text-sm">
                                    <span class="text-green-600">{{ t('orders.discount') }}</span>
                                    <span class="font-medium text-green-600">-{{ formatCurrency(order.discount) }}</span>
                                </div>
                                <div class="flex items-center justify-between border-t pt-2">
                                    <span class="text-lg font-semibold">{{ t('orders.total') }}</span>
                                    <span class="text-lg font-bold text-indigo-600">{{ formatCurrency(order.total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Shipping Info -->
                <div class="rounded-lg border bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">{{ t('orders.shipping') }}</h2>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p class="font-medium text-gray-900">{{ order.shipping_name }}</p>
                        <p>{{ order.shipping_phone }}</p>
                        <p>{{ order.shipping_address }}</p>
                        <p>{{ order.shipping_city }}, {{ order.shipping_province }} {{ order.shipping_postal_code }}</p>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="rounded-lg border bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">{{ t('orders.payment') }}</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ t('orders.payment_method') }}</span>
                            <span class="font-medium text-gray-900">{{ order.payment_method }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ t('orders.payment_status') }}</span>
                            <span :class="paymentStatusColor(order.payment_status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                {{ order.payment_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

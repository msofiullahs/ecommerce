<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface Order {
    id: number;
    order_number: string;
    total: number;
    status: string;
    created_at: string;
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
</script>

<template>
    <Head :title="t('order_success.title')" />

    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="rounded-lg border bg-white p-8 text-center shadow-sm">
            <!-- Success Icon -->
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="mb-2 text-2xl font-bold text-gray-900">
                {{ t('order_success.title') }}
            </h1>
            <p class="mb-6 text-gray-600">
                {{ t('order_success.message') }}
            </p>

            <!-- Order Details -->
            <div class="mb-8 rounded-lg bg-gray-50 p-6">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ t('order_success.order_number') }}</span>
                    <span class="text-lg font-bold text-gray-900">#{{ order.order_number }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ t('orders.total') }}</span>
                    <span class="text-lg font-bold text-indigo-600">{{ formatCurrency(order.total) }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ t('orders.status') }}</span>
                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800">
                        {{ order.status }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <Link
                    :href="route('orders.show', order.id)"
                    class="inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    {{ t('order_success.view_order') }}
                </Link>
                <Link
                    :href="route('products.index')"
                    class="inline-block rounded-lg border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    {{ t('order_success.continue_shopping') }}
                </Link>
            </div>
        </div>
    </div>
</template>

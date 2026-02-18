<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ order: any; allowedTransitions: Array<{ value: string }> }>();

function updateStatus(status: string) {
    if (confirm(`Change status to ${status}?`)) {
        router.put(route('admin.orders.update-status', props.order.id), { status });
    }
}

function formatCurrency(val: number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val); }
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Order {{ order.order_number }}</h1>
            <div class="flex gap-2">
                <Button v-for="transition in allowedTransitions" :key="transition.value"
                    :variant="transition.value === 'cancelled' ? 'danger' : 'primary'" size="sm"
                    @click="updateStatus(transition.value)">
                    {{ transition.value }}
                </Button>
            </div>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <Card>
                <h3 class="mb-3 font-semibold">Order Details</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><Badge>{{ order.status }}</Badge></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Payment</dt><dd><Badge :color="order.payment_status === 'paid' ? 'green' : 'yellow'">{{ order.payment_status }}</Badge></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd>{{ formatCurrency(order.subtotal) }}</dd></div>
                    <div v-if="order.discount_amount > 0" class="flex justify-between"><dt class="text-gray-500">Discount</dt><dd class="text-red-600">-{{ formatCurrency(order.discount_amount) }}</dd></div>
                    <div class="flex justify-between border-t pt-2 font-semibold"><dt>Total</dt><dd>{{ formatCurrency(order.total) }}</dd></div>
                </dl>
            </Card>
            <Card>
                <h3 class="mb-3 font-semibold">Shipping</h3>
                <dl class="space-y-1 text-sm">
                    <dd>{{ order.shipping_name }}</dd>
                    <dd>{{ order.shipping_phone }}</dd>
                    <dd>{{ order.shipping_address }}</dd>
                    <dd>{{ order.shipping_city }}, {{ order.shipping_province }} {{ order.shipping_postal_code }}</dd>
                </dl>
            </Card>
        </div>
        <Card class="mt-6">
            <h3 class="mb-3 font-semibold">Items</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">SKU</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Qty</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Subtotal</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="item in order.items" :key="item.id">
                        <td class="px-4 py-3 text-sm">{{ item.product_name }} <span v-if="item.variant_name" class="text-gray-500">- {{ item.variant_name }}</span></td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ item.sku }}</td>
                        <td class="px-4 py-3 text-sm">{{ formatCurrency(item.price) }}</td>
                        <td class="px-4 py-3 text-sm">{{ item.quantity }}</td>
                        <td class="px-4 py-3 text-sm">{{ formatCurrency(item.subtotal) }}</td>
                    </tr>
                </tbody>
            </table>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ movements: { data: any[]; links: any[] }; filters: any; types: any[] }>();
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Stock Movements</h1>
        <DataTable :columns="[{ key: 'date', label: 'Date' }, { key: 'product', label: 'Product' }, { key: 'variant', label: 'Variant' }, { key: 'type', label: 'Type' }, { key: 'qty', label: 'Quantity' }, { key: 'user', label: 'By' }, { key: 'notes', label: 'Notes' }]" :paginator="movements">
            <tr v-for="m in movements.data" :key="m.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-500">{{ new Date(m.created_at).toLocaleString() }}</td>
                <td class="px-6 py-4 text-sm">{{ m.product_variant?.product?.name }}</td>
                <td class="px-6 py-4 text-sm">{{ m.product_variant?.name }}</td>
                <td class="px-6 py-4"><Badge>{{ m.type }}</Badge></td>
                <td class="px-6 py-4 text-sm" :class="m.quantity > 0 ? 'text-green-600' : 'text-red-600'">{{ m.quantity > 0 ? '+' : '' }}{{ m.quantity }}</td>
                <td class="px-6 py-4 text-sm">{{ m.user?.name || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ m.notes || '-' }}</td>
            </tr>
        </DataTable>
    </div>
</template>

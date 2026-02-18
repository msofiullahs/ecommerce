<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ orders: { data: any[]; links: any[] }; filters: any; statuses: any[] }>();
const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

function applyFilters() {
    router.get(route('admin.orders.index'), { search: search.value, status: statusFilter.value }, { preserveState: true });
}

function formatCurrency(val: number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Orders</h1>
        <div class="mb-4 flex gap-4">
            <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search order #..." class="rounded-lg border-gray-300 text-sm" />
            <select v-model="statusFilter" @change="applyFilters" class="rounded-lg border-gray-300 text-sm">
                <option value="">All Statuses</option>
                <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
            </select>
        </div>
        <DataTable :columns="[{ key: 'order', label: 'Order #' }, { key: 'customer', label: 'Customer' }, { key: 'total', label: 'Total' }, { key: 'status', label: 'Status' }, { key: 'payment', label: 'Payment' }, { key: 'date', label: 'Date' }]" :paginator="orders">
            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm"><Link :href="route('admin.orders.show', order.id)" class="text-blue-600 hover:underline">{{ order.order_number }}</Link></td>
                <td class="px-6 py-4 text-sm">{{ order.user?.name || 'Guest' }}</td>
                <td class="px-6 py-4 text-sm">{{ formatCurrency(order.total) }}</td>
                <td class="px-6 py-4"><Badge>{{ order.status }}</Badge></td>
                <td class="px-6 py-4"><Badge :color="order.payment_status === 'paid' ? 'green' : 'yellow'">{{ order.payment_status }}</Badge></td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ new Date(order.created_at).toLocaleDateString() }}</td>
            </tr>
        </DataTable>
    </div>
</template>

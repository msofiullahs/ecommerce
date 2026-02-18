<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ report: any[]; filters: { from: string; to: string } }>();
const from = ref(props.filters.from);
const to = ref(props.filters.to);

function applyFilters() { router.get(route('admin.reports.products'), { from: from.value, to: to.value }, { preserveState: true }); }
function formatCurrency(val: number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Product Report</h1>
        <div class="mb-4 flex items-end gap-4">
            <div><label class="mb-1 block text-sm font-medium">From</label><input v-model="from" type="date" class="rounded-lg border-gray-300" /></div>
            <div><label class="mb-1 block text-sm font-medium">To</label><input v-model="to" type="date" class="rounded-lg border-gray-300" /></div>
            <Button @click="applyFilters">Apply</Button>
        </div>
        <DataTable :columns="[{ key: 'name', label: 'Product' }, { key: 'qty', label: 'Qty Sold' }, { key: 'rev', label: 'Revenue' }]">
            <tr v-for="item in report" :key="item.product_id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">{{ item.product_name }}</td>
                <td class="px-6 py-4 text-sm">{{ item.total_quantity }}</td>
                <td class="px-6 py-4 text-sm">{{ formatCurrency(item.total_revenue) }}</td>
            </tr>
        </DataTable>
    </div>
</template>

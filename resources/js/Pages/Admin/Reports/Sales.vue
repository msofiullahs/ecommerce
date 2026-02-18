<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import StatCard from '@/Components/Admin/Data/StatCard.vue';
import LineChart from '@/Components/Admin/Charts/LineChart.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ report: any; filters: { from: string; to: string } }>();
const from = ref(props.filters.from);
const to = ref(props.filters.to);

function applyFilters() { router.get(route('admin.reports.sales'), { from: from.value, to: to.value }, { preserveState: true }); }
function formatCurrency(val: number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val); }

const chartLabels = computed(() => props.report.daily_breakdown?.map((d: any) => d.date) || []);
const chartData = computed(() => props.report.daily_breakdown?.map((d: any) => d.revenue) || []);
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Sales Report</h1>
        <div class="mb-4 flex items-end gap-4">
            <div><label class="mb-1 block text-sm font-medium">From</label><input v-model="from" type="date" class="rounded-lg border-gray-300" /></div>
            <div><label class="mb-1 block text-sm font-medium">To</label><input v-model="to" type="date" class="rounded-lg border-gray-300" /></div>
            <Button @click="applyFilters">Apply</Button>
            <a :href="route('admin.reports.sales.export.excel', { from, to })" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Export Excel</a>
            <a :href="route('admin.reports.sales.export.pdf', { from, to })" class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">Export PDF</a>
        </div>
        <div class="mb-6 grid gap-4 sm:grid-cols-4">
            <StatCard title="Total Revenue" :value="formatCurrency(report.total_revenue)" />
            <StatCard title="Total Orders" :value="String(report.total_orders)" />
            <StatCard title="Avg Order Value" :value="formatCurrency(report.average_order_value)" />
            <StatCard title="Total Discounts" :value="formatCurrency(report.total_discount)" />
        </div>
        <Card>
            <h3 class="mb-4 text-lg font-semibold">Daily Revenue</h3>
            <LineChart :labels="chartLabels" :datasets="[{ label: 'Revenue', data: chartData }]" />
        </Card>
    </div>
</template>

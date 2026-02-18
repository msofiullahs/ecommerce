<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import StatCard from '@/Components/Admin/Data/StatCard.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import LineChart from '@/Components/Admin/Charts/LineChart.vue';
import BarChart from '@/Components/Admin/Charts/BarChart.vue';
import DoughnutChart from '@/Components/Admin/Charts/DoughnutChart.vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { computed } from 'vue';

defineOptions({ layout: AdminLayout });

const { t } = useI18n();

const props = defineProps<{
    stats: Record<string, number>;
    revenueChart: Array<{ date: string; revenue: number; orders: number }>;
    topProducts: Array<{ product_name: string; total_sold: number; total_revenue: number }>;
    recentOrders: Array<{ id: number; order_number: string; total: number; status: string; created_at: string; user?: { name: string } }>;
    ordersByStatus: Array<{ status: string; count: number }>;
}>();

const revenueLabels = computed(() => props.revenueChart.map(d => d.date));
const revenueData = computed(() => props.revenueChart.map(d => d.revenue));
const statusLabels = computed(() => props.ordersByStatus.map(s => s.status));
const statusData = computed(() => props.ordersByStatus.map(s => s.count));
const topProductLabels = computed(() => props.topProducts.map(p => p.product_name));
const topProductData = computed(() => props.topProducts.map(p => p.total_revenue));

function formatCurrency(val: number): string {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold text-gray-900">{{ t('sidebar.dashboard') }}</h1>

        <!-- Stat Cards -->
        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <StatCard :title="t('dashboard.revenue_today')" :value="formatCurrency(stats.total_revenue_today)" />
            <StatCard :title="t('dashboard.orders_today')" :value="String(stats.total_orders_today)" />
            <StatCard :title="t('dashboard.revenue_month')" :value="formatCurrency(stats.total_revenue_month)" />
            <StatCard :title="t('dashboard.pending_orders')" :value="String(stats.pending_orders)" />
        </div>

        <!-- Revenue Chart -->
        <Card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold">{{ t('dashboard.revenue_trend') }}</h3>
            <LineChart :labels="revenueLabels" :datasets="[{ label: 'Revenue', data: revenueData }]" />
        </Card>

        <!-- Charts Row -->
        <div class="mb-6 grid gap-6 lg:grid-cols-2">
            <Card>
                <h3 class="mb-4 text-lg font-semibold">{{ t('dashboard.top_products') }}</h3>
                <BarChart :labels="topProductLabels" :datasets="[{ label: 'Revenue', data: topProductData }]" />
            </Card>
            <Card>
                <h3 class="mb-4 text-lg font-semibold">{{ t('dashboard.orders_by_status') }}</h3>
                <DoughnutChart :labels="statusLabels" :data="statusData" />
            </Card>
        </div>

        <!-- Recent Orders -->
        <Card>
            <h3 class="mb-4 text-lg font-semibold">{{ t('dashboard.recent_orders') }}</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Order #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="order in recentOrders" :key="order.id">
                        <td class="px-4 py-3">
                            <Link :href="route('admin.orders.show', order.id)" class="text-blue-600 hover:underline">
                                {{ order.order_number }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ order.user?.name || 'Guest' }}</td>
                        <td class="px-4 py-3 text-sm">{{ formatCurrency(order.total) }}</td>
                        <td class="px-4 py-3"><Badge>{{ order.status }}</Badge></td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                    </tr>
                </tbody>
            </table>
        </Card>
    </div>
</template>

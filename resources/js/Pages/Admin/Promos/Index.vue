<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { Link, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ promos: { data: any[]; links: any[] }; filters: any }>();
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Promotions</h1>
            <Link :href="route('admin.promos.create')"><Button>Add Promo</Button></Link>
        </div>
        <DataTable :columns="[{ key: 'code', label: 'Code' }, { key: 'type', label: 'Type' }, { key: 'value', label: 'Value' }, { key: 'usage', label: 'Usage' }, { key: 'status', label: 'Status' }, { key: 'actions', label: 'Actions' }]" :paginator="promos">
            <tr v-for="promo in promos.data" :key="promo.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-mono font-medium">{{ promo.code }}</td>
                <td class="px-6 py-4 text-sm capitalize">{{ promo.type }}</td>
                <td class="px-6 py-4 text-sm">{{ promo.type === 'percentage' ? promo.value + '%' : promo.value }}</td>
                <td class="px-6 py-4 text-sm">{{ promo.usage_count }} / {{ promo.usage_limit || '\u221E' }}</td>
                <td class="px-6 py-4"><Badge :color="promo.is_active ? 'green' : 'red'">{{ promo.is_active ? 'Active' : 'Inactive' }}</Badge></td>
                <td class="px-6 py-4 text-sm">
                    <Link :href="route('admin.promos.edit', promo.id)" class="mr-2 text-blue-600 hover:underline">Edit</Link>
                    <button @click="router.delete(route('admin.promos.destroy', promo.id))" class="text-red-600 hover:underline">Delete</button>
                </td>
            </tr>
        </DataTable>
    </div>
</template>

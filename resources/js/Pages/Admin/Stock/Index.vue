<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import Modal from '@/Components/Admin/UI/Modal.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ variants: { data: any[]; links: any[] }; filters: any; lowStockCount: number; outOfStockCount: number }>();

const showModal = ref(false);
const adjustForm = useForm({ product_variant_id: null as number | null, quantity: 0, type: 'adjustment', notes: '' });

function openAdjust(variantId: number) {
    adjustForm.product_variant_id = variantId;
    adjustForm.quantity = 0;
    adjustForm.notes = '';
    showModal.value = true;
}

function submitAdjust() {
    adjustForm.post(route('admin.stock.adjust'), { onSuccess: () => { showModal.value = false; } });
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Stock Management</h1>
            <Link :href="route('admin.stock.movements')"><Button variant="secondary">View Movements</Button></Link>
        </div>
        <div class="mb-4 flex gap-4">
            <Badge color="yellow">Low Stock: {{ lowStockCount }}</Badge>
            <Badge color="red">Out of Stock: {{ outOfStockCount }}</Badge>
        </div>
        <DataTable :columns="[{ key: 'product', label: 'Product' }, { key: 'variant', label: 'Variant' }, { key: 'sku', label: 'SKU' }, { key: 'stock', label: 'Stock' }, { key: 'threshold', label: 'Threshold' }, { key: 'status', label: 'Status' }, { key: 'actions', label: '' }]" :paginator="variants">
            <tr v-for="v in variants.data" :key="v.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">{{ v.product?.name }}</td>
                <td class="px-6 py-4 text-sm">{{ v.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ v.sku }}</td>
                <td class="px-6 py-4 text-sm font-medium">{{ v.stock }}</td>
                <td class="px-6 py-4 text-sm">{{ v.low_stock_threshold }}</td>
                <td class="px-6 py-4"><Badge :color="v.stock > v.low_stock_threshold ? 'green' : v.stock > 0 ? 'yellow' : 'red'">{{ v.stock > v.low_stock_threshold ? 'OK' : v.stock > 0 ? 'Low' : 'Out' }}</Badge></td>
                <td class="px-6 py-4 text-sm"><button @click="openAdjust(v.id)" class="text-blue-600 hover:underline">Adjust</button></td>
            </tr>
        </DataTable>

        <Modal :show="showModal" @close="showModal = false">
            <h3 class="mb-4 text-lg font-semibold">Adjust Stock</h3>
            <form @submit.prevent="submitAdjust" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium">Quantity (positive to add, negative to remove)</label>
                    <input v-model.number="adjustForm.quantity" type="number" class="w-full rounded-lg border-gray-300" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Type</label>
                    <select v-model="adjustForm.type" class="w-full rounded-lg border-gray-300">
                        <option value="adjustment">Adjustment</option>
                        <option value="purchase">Purchase</option>
                        <option value="return">Return</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Notes</label>
                    <textarea v-model="adjustForm.notes" class="w-full rounded-lg border-gray-300" rows="2" />
                </div>
                <div class="flex justify-end gap-2">
                    <Button variant="secondary" @click="showModal = false">Cancel</Button>
                    <Button type="submit" :loading="adjustForm.processing">Save</Button>
                </div>
            </form>
        </Modal>
    </div>
</template>

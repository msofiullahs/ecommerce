<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref, watch } from 'vue';

defineOptions({ layout: AdminLayout });
const { t } = useI18n();

const props = defineProps<{
    products: { data: any[]; links: any[] };
    categories: any[];
    filters: { search?: string; category_id?: string; status?: string };
}>();

const search = ref(props.filters.search || '');
const categoryId = ref(props.filters.category_id || '');

function applyFilters() {
    router.get(route('admin.products.index'), { search: search.value, category_id: categoryId.value }, { preserveState: true });
}

function deleteProduct(id: number) {
    if (confirm('Are you sure?')) {
        router.delete(route('admin.products.destroy', id));
    }
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">{{ t('products.title') }}</h1>
            <Link :href="route('admin.products.create')">
                <Button>{{ t('products.add_new') }}</Button>
            </Link>
        </div>

        <!-- Filters -->
        <div class="mb-4 flex gap-4">
            <input v-model="search" @keyup.enter="applyFilters" type="text" :placeholder="t('actions.search')" class="rounded-lg border-gray-300 text-sm" />
            <select v-model="categoryId" @change="applyFilters" class="rounded-lg border-gray-300 text-sm">
                <option value="">All Categories</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
        </div>

        <DataTable :columns="[
            { key: 'name', label: t('products.name') },
            { key: 'sku', label: 'SKU' },
            { key: 'price', label: t('products.price') },
            { key: 'stock', label: t('products.stock') },
            { key: 'status', label: t('products.status') },
            { key: 'actions', label: t('actions.actions') },
        ]" :paginator="products">
            <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ product.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ product.sku }}</td>
                <td class="px-6 py-4 text-sm">{{ product.price }}</td>
                <td class="px-6 py-4 text-sm">{{ product.variants?.reduce((sum: number, v: any) => sum + v.stock, 0) || 0 }}</td>
                <td class="px-6 py-4">
                    <Badge :color="product.is_active ? 'green' : 'red'">{{ product.is_active ? 'Active' : 'Inactive' }}</Badge>
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <Link :href="route('admin.products.edit', product.id)" class="text-blue-600 hover:underline">{{ t('actions.edit') }}</Link>
                        <button @click="deleteProduct(product.id)" class="text-red-600 hover:underline">{{ t('actions.delete') }}</button>
                    </div>
                </td>
            </tr>
        </DataTable>
    </div>
</template>

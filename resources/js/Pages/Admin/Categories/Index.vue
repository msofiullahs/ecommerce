<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { Link, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ categories: { data: any[]; links: any[] }; filters: any }>();

function deleteCategory(id: number) {
    if (confirm('Are you sure?')) router.delete(route('admin.categories.destroy', id));
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Categories</h1>
            <Link :href="route('admin.categories.create')"><Button>Add Category</Button></Link>
        </div>
        <DataTable :columns="[{ key: 'name', label: 'Name' }, { key: 'products', label: 'Products' }, { key: 'status', label: 'Status' }, { key: 'actions', label: 'Actions' }]" :paginator="categories">
            <tr v-for="cat in categories.data" :key="cat.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium">{{ cat.name }}</td>
                <td class="px-6 py-4 text-sm">{{ cat.products_count }}</td>
                <td class="px-6 py-4"><Badge :color="cat.is_active ? 'green' : 'red'">{{ cat.is_active ? 'Active' : 'Inactive' }}</Badge></td>
                <td class="px-6 py-4 text-sm">
                    <Link :href="route('admin.categories.edit', cat.id)" class="mr-2 text-blue-600 hover:underline">Edit</Link>
                    <button @click="deleteCategory(cat.id)" class="text-red-600 hover:underline">Delete</button>
                </td>
            </tr>
        </DataTable>
    </div>
</template>

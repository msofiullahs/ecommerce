<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { Link, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ pages: { data: any[]; links: any[] } }>();

function deletePage(id: number) {
    if (confirm('Are you sure?')) router.delete(route('admin.pages.destroy', id));
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Pages</h1>
            <Link :href="route('admin.pages.create')"><Button>Add Page</Button></Link>
        </div>
        <DataTable :columns="[{ key: 'title', label: 'Title' }, { key: 'slug', label: 'Slug' }, { key: 'status', label: 'Status' }, { key: 'updated', label: 'Updated' }, { key: 'actions', label: 'Actions' }]" :paginator="pages">
            <tr v-for="page in pages.data" :key="page.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium">{{ page.title }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">/{{ page.slug }}</td>
                <td class="px-6 py-4">
                    <Badge :color="page.is_published ? 'green' : 'yellow'">{{ page.is_published ? 'Published' : 'Draft' }}</Badge>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ new Date(page.updated_at).toLocaleDateString() }}</td>
                <td class="px-6 py-4 text-sm">
                    <Link :href="route('admin.pages.edit', page.id)" class="mr-2 text-blue-600 hover:underline">Edit</Link>
                    <button @click="deletePage(page.id)" class="text-red-600 hover:underline">Delete</button>
                </td>
            </tr>
        </DataTable>
    </div>
</template>

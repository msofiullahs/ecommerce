<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ templates: any[] }>();
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Email Templates</h1>
        <DataTable :columns="[{ key: 'name', label: 'Template' }, { key: 'subject', label: 'Subject' }, { key: 'status', label: 'Status' }, { key: 'actions', label: 'Actions' }]">
            <tr v-for="tpl in templates" :key="tpl.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium">{{ tpl.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ tpl.subject }}</td>
                <td class="px-6 py-4">
                    <Badge :color="tpl.is_active ? 'green' : 'red'">{{ tpl.is_active ? 'Active' : 'Inactive' }}</Badge>
                </td>
                <td class="px-6 py-4 text-sm">
                    <Link :href="route('admin.email-templates.edit', tpl.id)" class="text-blue-600 hover:underline">Edit</Link>
                </td>
            </tr>
        </DataTable>
    </div>
</template>

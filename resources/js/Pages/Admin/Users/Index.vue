<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import DataTable from '@/Components/Admin/Data/DataTable.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ users: { data: any[]; links: any[] }; filters: any; roles: any[] }>();
const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || '');

function applyFilters() {
    router.get(route('admin.users.index'), { search: search.value, role: roleFilter.value }, { preserveState: true });
}

function deleteUser(id: number) {
    if (confirm('Are you sure you want to delete this user?')) router.delete(route('admin.users.destroy', id));
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Users</h1>
            <Link :href="route('admin.users.create')"><Button>Add User</Button></Link>
        </div>
        <div class="mb-4 flex gap-4">
            <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search users..." class="rounded-lg border-gray-300 text-sm" />
            <select v-model="roleFilter" @change="applyFilters" class="rounded-lg border-gray-300 text-sm">
                <option value="">All Roles</option>
                <option v-for="r in roles" :key="r.value" :value="r.value">{{ r.label }}</option>
            </select>
        </div>
        <DataTable :columns="[{ key: 'name', label: 'Name' }, { key: 'email', label: 'Email' }, { key: 'role', label: 'Role' }, { key: 'orders', label: 'Orders' }, { key: 'joined', label: 'Joined' }, { key: 'actions', label: 'Actions' }]" :paginator="users">
            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium">{{ user.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ user.email }}</td>
                <td class="px-6 py-4">
                    <Badge :color="user.role === 'admin' ? 'indigo' : user.role === 'staff' ? 'blue' : 'gray'">{{ user.role }}</Badge>
                </td>
                <td class="px-6 py-4 text-sm">{{ user.orders_count || 0 }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                <td class="px-6 py-4 text-sm">
                    <Link :href="route('admin.users.edit', user.id)" class="mr-2 text-blue-600 hover:underline">Edit</Link>
                    <button @click="deleteUser(user.id)" class="text-red-600 hover:underline">Delete</button>
                </td>
            </tr>
        </DataTable>
    </div>
</template>

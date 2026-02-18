<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ user: any; roles: any[] }>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role,
    phone: props.user.phone || '',
});

function submit() { form.put(route('admin.users.update', props.user.id)); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Edit User</h1>
        <form @submit.prevent="submit">
            <Card class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Name *</label>
                        <input v-model="form.name" type="text" class="w-full rounded-lg border-gray-300" />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Email *</label>
                        <input v-model="form.email" type="email" class="w-full rounded-lg border-gray-300" />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">New Password <span class="text-gray-400">(leave blank to keep current)</span></label>
                        <input v-model="form.password" type="password" class="w-full rounded-lg border-gray-300" />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Confirm Password</label>
                        <input v-model="form.password_confirmation" type="password" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Role</label>
                        <select v-model="form.role" class="w-full rounded-lg border-gray-300">
                            <option v-for="r in roles" :key="r.value" :value="r.value">{{ r.label }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Phone</label>
                        <input v-model="form.phone" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                </div>
            </Card>
            <div class="mt-4 flex justify-end gap-3">
                <Button variant="secondary" type="button" @click="router.visit(route('admin.users.index'))">Cancel</Button>
                <Button type="submit" :loading="form.processing">Save Changes</Button>
            </div>
        </form>
    </div>
</template>

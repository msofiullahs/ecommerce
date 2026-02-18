<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

const form = useForm({
    code: '', name: { en: '', id: '' }, description: { en: '', id: '' }, type: 'percentage', value: 0,
    minimum_order: null as number | null, maximum_discount: null as number | null,
    usage_limit: null as number | null, per_user_limit: null as number | null,
    starts_at: '', expires_at: '', is_active: true,
});

function submit() { form.post(route('admin.promos.store')); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Create Promo</h1>
        <form @submit.prevent="submit">
            <Card class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="mb-1 block text-sm font-medium">Code *</label><input v-model="form.code" type="text" class="w-full rounded-lg border-gray-300 font-mono uppercase" /></div>
                    <TranslatableInput v-model="form.name" label="Name" required />
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <div><label class="mb-1 block text-sm font-medium">Type</label><select v-model="form.type" class="w-full rounded-lg border-gray-300"><option value="percentage">Percentage</option><option value="fixed">Fixed Amount</option></select></div>
                    <div><label class="mb-1 block text-sm font-medium">Value *</label><input v-model.number="form.value" type="number" step="0.01" class="w-full rounded-lg border-gray-300" /></div>
                    <div><label class="mb-1 block text-sm font-medium">Minimum Order</label><input v-model.number="form.minimum_order" type="number" step="0.01" class="w-full rounded-lg border-gray-300" /></div>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <div><label class="mb-1 block text-sm font-medium">Usage Limit</label><input v-model.number="form.usage_limit" type="number" class="w-full rounded-lg border-gray-300" /></div>
                    <div><label class="mb-1 block text-sm font-medium">Starts At</label><input v-model="form.starts_at" type="datetime-local" class="w-full rounded-lg border-gray-300" /></div>
                    <div><label class="mb-1 block text-sm font-medium">Expires At</label><input v-model="form.expires_at" type="datetime-local" class="w-full rounded-lg border-gray-300" /></div>
                </div>
                <label class="flex items-center gap-2"><input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-blue-600" /><span class="text-sm">Active</span></label>
            </Card>
            <div class="mt-4 flex justify-end"><Button type="submit" :loading="form.processing">Save</Button></div>
        </form>
    </div>
</template>

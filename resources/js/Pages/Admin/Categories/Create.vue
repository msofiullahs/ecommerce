<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ parentCategories: any[]; locales: string[] }>();

const form = useForm({
    name: { en: '', id: '' },
    description: { en: '', id: '' },
    slug: '',
    parent_id: null as number | null,
    sort_order: 0,
    is_active: true,
});

function generateSlug() {
    form.slug = (form.name.en || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

function submit() { form.post(route('admin.categories.store')); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Create Category</h1>
        <form @submit.prevent="submit">
            <Card class="space-y-4">
                <TranslatableInput v-model="form.name" label="Category Name" required />
                <TranslatableInput v-model="form.description" label="Description" type="textarea" />
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Slug *</label>
                        <div class="flex gap-2">
                            <input v-model="form.slug" type="text" class="flex-1 rounded-lg border-gray-300" />
                            <Button type="button" variant="secondary" size="sm" @click="generateSlug">Gen</Button>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Parent</label>
                        <select v-model="form.parent_id" class="w-full rounded-lg border-gray-300">
                            <option :value="null">None</option>
                            <option v-for="p in parentCategories" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Sort Order</label>
                        <input v-model.number="form.sort_order" type="number" class="w-full rounded-lg border-gray-300" />
                    </div>
                </div>
                <label class="flex items-center gap-2"><input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-blue-600" /><span class="text-sm">Active</span></label>
            </Card>
            <div class="mt-4 flex justify-end"><Button type="submit" :loading="form.processing">Save</Button></div>
        </form>
    </div>
</template>

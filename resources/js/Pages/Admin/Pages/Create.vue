<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ locales: string[] }>();

const form = useForm({
    title: { en: '', id: '' },
    slug: '',
    content: { en: '', id: '' },
    meta_title: { en: '', id: '' },
    meta_description: { en: '', id: '' },
    is_published: false,
});

function generateSlug() {
    form.slug = (form.title.en || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

function submit() { form.post(route('admin.pages.store')); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Create Page</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <Card class="space-y-4">
                <TranslatableInput v-model="form.title" label="Page Title" required />
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Slug *</label>
                    <div class="flex gap-2">
                        <input v-model="form.slug" type="text" class="flex-1 rounded-lg border-gray-300" />
                        <Button type="button" variant="secondary" size="sm" @click="generateSlug">Generate</Button>
                    </div>
                    <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
                </div>
                <TranslatableInput v-model="form.content" label="Content" type="textarea" />
            </Card>

            <Card>
                <h3 class="mb-4 text-lg font-semibold">SEO</h3>
                <div class="space-y-4">
                    <TranslatableInput v-model="form.meta_title" label="Meta Title" />
                    <TranslatableInput v-model="form.meta_description" label="Meta Description" type="textarea" />
                </div>
            </Card>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <input v-model="form.is_published" type="checkbox" class="rounded border-gray-300 text-blue-600" />
                    <span class="text-sm">Publish immediately</span>
                </label>
                <div class="flex gap-3">
                    <Button variant="secondary" type="button" @click="$inertia.visit(route('admin.pages.index'))">Cancel</Button>
                    <Button type="submit" :loading="form.processing">Save</Button>
                </div>
            </div>
        </form>
    </div>
</template>

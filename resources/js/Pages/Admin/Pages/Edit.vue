<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ page: any; locales: string[] }>();

const form = useForm({
    title: props.page.title || { en: '', id: '' },
    slug: props.page.slug,
    content: props.page.content || { en: '', id: '' },
    meta_title: props.page.meta_title || { en: '', id: '' },
    meta_description: props.page.meta_description || { en: '', id: '' },
    is_published: props.page.is_published,
});

function submit() { form.put(route('admin.pages.update', props.page.id)); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Edit Page</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <Card class="space-y-4">
                <TranslatableInput v-model="form.title" label="Page Title" required />
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Slug</label>
                    <input v-model="form.slug" type="text" class="w-full rounded-lg border-gray-300" />
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
                    <span class="text-sm">Published</span>
                </label>
                <div class="flex gap-3">
                    <Button variant="secondary" type="button" @click="router.visit(route('admin.pages.index'))">Cancel</Button>
                    <Button type="submit" :loading="form.processing">Save</Button>
                </div>
            </div>
        </form>
    </div>
</template>

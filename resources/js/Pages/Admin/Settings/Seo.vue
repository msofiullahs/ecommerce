<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ settings: any[]; locales: string[] }>();

function getVal(key: string) { return props.settings.find((s: any) => s.key === key)?.value; }

const form = useForm({
    meta_title: getVal('meta_title') || { en: '', id: '' },
    meta_description: getVal('meta_description') || { en: '', id: '' },
    meta_keywords: getVal('meta_keywords') || '',
    google_analytics_id: getVal('google_analytics_id') || '',
    facebook_pixel_id: getVal('facebook_pixel_id') || '',
});

function submit() { form.put(route('admin.settings.seo.update')); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">SEO Settings</h1>
        <form @submit.prevent="submit">
            <Card class="space-y-4">
                <TranslatableInput v-model="form.meta_title" label="Default Meta Title" />
                <TranslatableInput v-model="form.meta_description" label="Default Meta Description" type="textarea" />
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Meta Keywords</label>
                        <input v-model="form.meta_keywords" type="text" class="w-full rounded-lg border-gray-300" placeholder="keyword1, keyword2, keyword3" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Google Analytics ID</label>
                        <input v-model="form.google_analytics_id" type="text" class="w-full rounded-lg border-gray-300" placeholder="G-XXXXXXXXXX" />
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Facebook Pixel ID</label>
                    <input v-model="form.facebook_pixel_id" type="text" class="w-full rounded-lg border-gray-300" />
                </div>
            </Card>
            <div class="mt-4 flex justify-end"><Button type="submit" :loading="form.processing">Save</Button></div>
        </form>
    </div>
</template>

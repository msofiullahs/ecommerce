<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ settings: any[]; locales: string[] }>();

function getVal(key: string) { return props.settings.find((s: any) => s.key === key)?.value || ''; }

const form = useForm({
    facebook_url: getVal('facebook_url'),
    instagram_url: getVal('instagram_url'),
    twitter_url: getVal('twitter_url'),
    tiktok_url: getVal('tiktok_url'),
    youtube_url: getVal('youtube_url'),
    whatsapp_number: getVal('whatsapp_number'),
});

function submit() { form.put(route('admin.settings.social.update')); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Social Media Settings</h1>
        <form @submit.prevent="submit">
            <Card class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Facebook URL</label>
                        <input v-model="form.facebook_url" type="url" class="w-full rounded-lg border-gray-300" placeholder="https://facebook.com/..." />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Instagram URL</label>
                        <input v-model="form.instagram_url" type="url" class="w-full rounded-lg border-gray-300" placeholder="https://instagram.com/..." />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Twitter URL</label>
                        <input v-model="form.twitter_url" type="url" class="w-full rounded-lg border-gray-300" placeholder="https://twitter.com/..." />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">TikTok URL</label>
                        <input v-model="form.tiktok_url" type="url" class="w-full rounded-lg border-gray-300" placeholder="https://tiktok.com/@..." />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">YouTube URL</label>
                        <input v-model="form.youtube_url" type="url" class="w-full rounded-lg border-gray-300" placeholder="https://youtube.com/..." />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">WhatsApp Number</label>
                        <input v-model="form.whatsapp_number" type="text" class="w-full rounded-lg border-gray-300" placeholder="+62..." />
                    </div>
                </div>
            </Card>
            <div class="mt-4 flex justify-end"><Button type="submit" :loading="form.processing">Save</Button></div>
        </form>
    </div>
</template>

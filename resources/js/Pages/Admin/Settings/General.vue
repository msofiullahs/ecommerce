<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ settings: any[]; locales: string[] }>();

function getVal(key: string) { return props.settings.find((s: any) => s.key === key)?.value; }

const form = useForm({
    site_name: getVal('site_name') || { en: '', id: '' },
    site_tagline: getVal('site_tagline') || { en: '', id: '' },
    currency: getVal('currency') || 'IDR',
    currency_symbol: getVal('currency_symbol') || 'Rp',
});

function submit() { form.put(route('admin.settings.general.update')); }
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">General Settings</h1>
        <form @submit.prevent="submit">
            <Card class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="mb-1 block text-sm font-medium">Currency</label><input v-model="form.currency" type="text" class="w-full rounded-lg border-gray-300" /></div>
                    <div><label class="mb-1 block text-sm font-medium">Currency Symbol</label><input v-model="form.currency_symbol" type="text" class="w-full rounded-lg border-gray-300" /></div>
                </div>
            </Card>
            <div class="mt-4 flex justify-end"><Button type="submit" :loading="form.processing">Save</Button></div>
        </form>
    </div>
</template>

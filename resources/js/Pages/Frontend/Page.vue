<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';

defineOptions({ layout: FrontendLayout });

interface PageData {
    id: number;
    title: string;
    slug: string;
    content: string;
}

defineProps<{
    page: PageData;
}>();
</script>

<template>
    <Head :title="page.title" />

    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">{{ page.title }}</h1>
        <div class="prose max-w-none rounded-lg border bg-white p-8 shadow-sm" v-html="page.content" />
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

defineProps<{
    links: PaginationLink[];
}>();
</script>

<template>
    <nav class="flex items-center justify-center gap-1" v-if="links.length > 3">
        <template v-for="link in links" :key="link.label">
            <Link
                v-if="link.url"
                :href="link.url"
                :class="[
                    'rounded-lg px-3 py-2 text-sm',
                    link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border'
                ]"
                v-html="link.label"
                preserve-scroll
            />
            <span v-else class="rounded-lg px-3 py-2 text-sm text-gray-400" v-html="link.label" />
        </template>
    </nav>
</template>

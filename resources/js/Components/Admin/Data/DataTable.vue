<script setup lang="ts">
import Pagination from '../UI/Pagination.vue';

defineProps<{
    columns: Array<{ key: string; label: string; sortable?: boolean }>;
    paginator?: { links: Array<{ url: string | null; label: string; active: boolean }> };
}>();
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <slot />
                </tbody>
            </table>
        </div>
        <div v-if="paginator" class="border-t px-6 py-4">
            <Pagination :links="paginator.links" />
        </div>
    </div>
</template>

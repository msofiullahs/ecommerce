<script setup lang="ts">
defineProps<{
    title: string;
    value: string | number;
    icon?: string;
    trend?: number;
    color?: string;
}>();
</script>

<template>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ title }}</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ value }}</p>
                <p v-if="trend !== undefined" :class="trend >= 0 ? 'text-green-600' : 'text-red-600'" class="mt-1 text-sm">
                    {{ trend >= 0 ? '+' : '' }}{{ trend }}%
                </p>
            </div>
        </div>
    </div>
</template>

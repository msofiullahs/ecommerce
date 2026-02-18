<script setup lang="ts">
interface Props {
    color?: 'green' | 'red' | 'yellow' | 'blue' | 'gray' | 'indigo' | 'purple' | 'emerald' | 'orange';
}

withDefaults(defineProps<Props>(), { color: 'gray' });

const colorClasses: Record<string, string> = {
    green: 'bg-green-100 text-green-800',
    red: 'bg-red-100 text-red-800',
    yellow: 'bg-yellow-100 text-yellow-800',
    blue: 'bg-blue-100 text-blue-800',
    gray: 'bg-gray-100 text-gray-800',
    indigo: 'bg-indigo-100 text-indigo-800',
    purple: 'bg-purple-100 text-purple-800',
    emerald: 'bg-emerald-100 text-emerald-800',
    orange: 'bg-orange-100 text-orange-800',
};
</script>

<template>
    <span :class="colorClasses[color]" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
        <slot />
    </span>
</template>

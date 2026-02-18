<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface Props {
    modelValue: Record<string, string>;
    label: string;
    type?: 'text' | 'textarea';
    error?: string;
    required?: boolean;
}

const props = withDefaults(defineProps<Props>(), { type: 'text', required: false });
const emit = defineEmits(['update:modelValue']);

const page = usePage();
const locales = computed(() => (page.props.locales as string[]) || ['en', 'id']);
const activeLocale = ref(locales.value[0]);

function updateValue(locale: string, value: string) {
    emit('update:modelValue', { ...props.modelValue, [locale]: value });
}
</script>

<template>
    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <div class="mb-2 flex gap-1">
            <button
                v-for="locale in locales"
                :key="locale"
                type="button"
                @click="activeLocale = locale"
                :class="[
                    activeLocale === locale ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200',
                    'rounded px-3 py-1 text-xs font-medium uppercase transition-colors'
                ]"
            >
                {{ locale }}
            </button>
        </div>
        <template v-for="locale in locales" :key="locale">
            <input
                v-if="type === 'text' && activeLocale === locale"
                type="text"
                :value="modelValue?.[locale] || ''"
                @input="updateValue(locale, ($event.target as HTMLInputElement).value)"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                :placeholder="`${label} (${locale.toUpperCase()})`"
            />
            <textarea
                v-else-if="type === 'textarea' && activeLocale === locale"
                :value="modelValue?.[locale] || ''"
                @input="updateValue(locale, ($event.target as HTMLTextAreaElement).value)"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                rows="4"
                :placeholder="`${label} (${locale.toUpperCase()})`"
            />
        </template>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

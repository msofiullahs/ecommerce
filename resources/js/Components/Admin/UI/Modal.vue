<script setup lang="ts">
import { watch } from 'vue';

interface Props {
    show: boolean;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
}

const props = withDefaults(defineProps<Props>(), { maxWidth: 'md' });
const emit = defineEmits(['close']);

const maxWidthClasses = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
};

watch(() => props.show, (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
});
</script>

<template>
    <Teleport to="body">
        <Transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="emit('close')" />
                <div :class="maxWidthClasses[maxWidth]" class="relative mx-auto mt-12 w-full rounded-xl bg-white p-6 shadow-xl transition-all">
                    <slot />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

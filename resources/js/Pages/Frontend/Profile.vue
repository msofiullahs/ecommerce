<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    address: string | null;
    city: string | null;
    province: string | null;
    postal_code: string | null;
}

const props = defineProps<{
    user: User;
}>();

const form = useForm({
    name: props.user.name,
    phone: props.user.phone || '',
    address: props.user.address || '',
    city: props.user.city || '',
    province: props.user.province || '',
    postal_code: props.user.postal_code || '',
    current_password: '',
    password: '',
    password_confirmation: '',
});

function save() {
    form.put(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.current_password = '';
            form.password = '';
            form.password_confirmation = '';
        },
    });
}
</script>

<template>
    <Head :title="t('profile.title')" />

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">{{ t('profile.title') }}</h1>

        <form @submit.prevent="save">
            <!-- Personal Information -->
            <div class="rounded-lg border bg-white p-6 shadow-sm">
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('profile.name') }}
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Email (readonly) -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('profile.email') }}
                        </label>
                        <input
                            :value="user.email"
                            type="email"
                            readonly
                            class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 shadow-sm"
                        />
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('profile.phone') }}
                        </label>
                        <input
                            v-model="form.phone"
                            type="tel"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.phone }"
                        />
                        <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                            {{ form.errors.phone }}
                        </p>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('profile.address') }}
                        </label>
                        <textarea
                            v-model="form.address"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.address }"
                        />
                        <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                            {{ form.errors.address }}
                        </p>
                    </div>

                    <!-- City + Province -->
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                {{ t('profile.city') }}
                            </label>
                            <input
                                v-model="form.city"
                                type="text"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': form.errors.city }"
                            />
                            <p v-if="form.errors.city" class="mt-1 text-sm text-red-600">
                                {{ form.errors.city }}
                            </p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                {{ t('profile.province') }}
                            </label>
                            <input
                                v-model="form.province"
                                type="text"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': form.errors.province }"
                            />
                            <p v-if="form.errors.province" class="mt-1 text-sm text-red-600">
                                {{ form.errors.province }}
                            </p>
                        </div>
                    </div>

                    <!-- Postal Code -->
                    <div class="sm:w-1/2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('profile.postal_code') }}
                        </label>
                        <input
                            v-model="form.postal_code"
                            type="text"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.postal_code }"
                        />
                        <p v-if="form.errors.postal_code" class="mt-1 text-sm text-red-600">
                            {{ form.errors.postal_code }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="mt-6 rounded-lg border bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">
                    {{ t('profile.change_password') }}
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('profile.current_password') }}
                        </label>
                        <input
                            v-model="form.current_password"
                            type="password"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.current_password }"
                        />
                        <p v-if="form.errors.current_password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.current_password }}
                        </p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                {{ t('profile.new_password') }}
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': form.errors.password }"
                            />
                            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                                {{ form.errors.password }}
                            </p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                {{ t('profile.confirm_password') }}
                            </label>
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-6 flex justify-end">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-indigo-600 px-8 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span v-if="form.processing">{{ t('common.loading') }}</span>
                    <span v-else>{{ t('profile.save') }}</span>
                </button>
            </div>
        </form>
    </div>
</template>

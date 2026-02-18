<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
}
</script>

<template>
    <Head :title="t('auth.register')" />

    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="mb-8 text-center">
                <Link :href="route('home')" class="text-3xl font-bold text-indigo-600">
                    My Store
                </Link>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">{{ t('auth.register') }}</h2>
            </div>

            <div class="rounded-lg border bg-white p-8 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('auth.name') }}
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            autocomplete="name"
                            autofocus
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('auth.email') }}
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.email }"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('auth.password') }}
                        </label>
                        <input
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('auth.confirm_password') }}
                        </label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <span v-if="form.processing">{{ t('common.loading') }}</span>
                        <span v-else>{{ t('auth.register') }}</span>
                    </button>
                </form>

                <!-- Login Link -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    {{ t('auth.has_account') }}
                    <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-800">
                        {{ t('auth.login') }}
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

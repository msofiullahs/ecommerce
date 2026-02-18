<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
}
</script>

<template>
    <Head :title="t('auth.login')" />

    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="mb-8 text-center">
                <Link :href="route('home')" class="text-3xl font-bold text-indigo-600">
                    My Store
                </Link>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">{{ t('auth.login') }}</h2>
            </div>

            <div class="rounded-lg border bg-white p-8 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ t('auth.email') }}
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            autofocus
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
                            autocomplete="current-password"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :class="{ 'border-red-500': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            />
                            <span class="text-sm text-gray-600">{{ t('auth.remember_me') }}</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <span v-if="form.processing">{{ t('common.loading') }}</span>
                        <span v-else>{{ t('auth.login') }}</span>
                    </button>
                </form>

                <!-- Register Link -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    {{ t('auth.no_account') }}
                    <Link :href="route('register')" class="font-medium text-indigo-600 hover:text-indigo-800">
                        {{ t('auth.register') }}
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

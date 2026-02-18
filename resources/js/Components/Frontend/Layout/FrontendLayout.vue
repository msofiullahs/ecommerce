<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const page = usePage();

const mobileMenuOpen = ref(false);
const userMenuOpen = ref(false);
const langMenuOpen = ref(false);

const user = computed(() => (page.props.auth as any)?.user ?? null);
const flash = computed(() => page.props.flash as Record<string, string | null> | undefined);
const cartCount = computed(() => (page.props as any).cartCount ?? 0);
const currentLocale = computed(() => (page.props.locale as string) || 'en');

const showFlash = ref(true);

function switchLocale(locale: string) {
    router.post(route('locale.switch', { locale }), {}, {
        preserveState: true,
        onSuccess: () => {
            langMenuOpen.value = false;
        },
    });
}

function logout() {
    router.post(route('logout'));
}

function closeFlash() {
    showFlash.value = false;
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <!-- Navbar -->
        <nav class="sticky top-0 z-50 border-b bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo + Nav Links -->
                    <div class="flex items-center gap-8">
                        <Link :href="route('home')" class="text-xl font-bold text-gray-900">
                            My Store
                        </Link>
                        <div class="hidden items-center gap-6 md:flex">
                            <Link
                                :href="route('home')"
                                class="text-sm font-medium text-gray-700 transition hover:text-gray-900"
                                :class="{ 'text-indigo-600': route().current('home') }"
                            >
                                {{ t('nav.home') }}
                            </Link>
                            <Link
                                :href="route('products.index')"
                                class="text-sm font-medium text-gray-700 transition hover:text-gray-900"
                                :class="{ 'text-indigo-600': route().current('products.*') }"
                            >
                                {{ t('nav.products') }}
                            </Link>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center gap-4">
                        <!-- Language Switcher -->
                        <div class="relative">
                            <button
                                @click="langMenuOpen = !langMenuOpen"
                                class="flex items-center gap-1 rounded-md px-2 py-1 text-sm text-gray-600 hover:bg-gray-100"
                            >
                                {{ currentLocale.toUpperCase() }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                v-if="langMenuOpen"
                                class="absolute right-0 mt-2 w-24 rounded-md border bg-white py-1 shadow-lg"
                            >
                                <button
                                    @click="switchLocale('en')"
                                    class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                    :class="{ 'font-semibold text-indigo-600': currentLocale === 'en' }"
                                >
                                    EN
                                </button>
                                <button
                                    @click="switchLocale('id')"
                                    class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                    :class="{ 'font-semibold text-indigo-600': currentLocale === 'id' }"
                                >
                                    ID
                                </button>
                            </div>
                        </div>

                        <!-- Cart Icon -->
                        <Link :href="route('cart.show')" class="relative p-2 text-gray-600 hover:text-gray-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                            <span
                                v-if="cartCount > 0"
                                class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white"
                            >
                                {{ cartCount }}
                            </span>
                        </Link>

                        <!-- User Menu -->
                        <div v-if="user" class="relative">
                            <button
                                @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100"
                            >
                                {{ user.name }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                v-if="userMenuOpen"
                                class="absolute right-0 mt-2 w-48 rounded-md border bg-white py-1 shadow-lg"
                            >
                                <Link
                                    :href="route('profile.edit')"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    @click="userMenuOpen = false"
                                >
                                    {{ t('nav.profile') }}
                                </Link>
                                <Link
                                    :href="route('orders.index')"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    @click="userMenuOpen = false"
                                >
                                    {{ t('nav.orders') }}
                                </Link>
                                <button
                                    @click="logout"
                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    {{ t('nav.logout') }}
                                </button>
                            </div>
                        </div>
                        <div v-else class="hidden items-center gap-3 md:flex">
                            <Link
                                :href="route('login')"
                                class="text-sm font-medium text-gray-700 hover:text-gray-900"
                            >
                                {{ t('nav.login') }}
                            </Link>
                            <Link
                                :href="route('register')"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                            >
                                {{ t('nav.register') }}
                            </Link>
                        </div>

                        <!-- Mobile menu button -->
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 md:hidden"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    v-if="!mobileMenuOpen"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    v-else
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="border-t md:hidden">
                <div class="space-y-1 px-4 py-3">
                    <Link
                        :href="route('home')"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                        @click="mobileMenuOpen = false"
                    >
                        {{ t('nav.home') }}
                    </Link>
                    <Link
                        :href="route('products.index')"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                        @click="mobileMenuOpen = false"
                    >
                        {{ t('nav.products') }}
                    </Link>
                    <template v-if="!user">
                        <Link
                            :href="route('login')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                            @click="mobileMenuOpen = false"
                        >
                            {{ t('nav.login') }}
                        </Link>
                        <Link
                            :href="route('register')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                            @click="mobileMenuOpen = false"
                        >
                            {{ t('nav.register') }}
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div v-if="showFlash && flash?.success" class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                <span>{{ flash.success }}</span>
                <button @click="closeFlash" class="text-green-600 hover:text-green-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div v-if="showFlash && flash?.error" class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <span>{{ flash.error }}</span>
                <button @click="closeFlash" class="text-red-600 hover:text-red-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t bg-white">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-bold text-gray-900">My Store</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ t('footer.about') }}</p>
                    </div>
                    <div class="flex gap-6">
                        <Link :href="route('home')" class="text-sm text-gray-500 hover:text-gray-900">
                            {{ t('nav.home') }}
                        </Link>
                        <Link :href="route('products.index')" class="text-sm text-gray-500 hover:text-gray-900">
                            {{ t('nav.products') }}
                        </Link>
                    </div>
                </div>
                <div class="mt-8 border-t pt-6 text-center text-sm text-gray-400">
                    &copy; {{ new Date().getFullYear() }} My Store. {{ t('footer.rights') }}
                </div>
            </div>
        </footer>
    </div>

    <!-- Click outside to close dropdowns -->
    <Teleport to="body">
        <div
            v-if="userMenuOpen || langMenuOpen"
            class="fixed inset-0 z-40"
            @click="userMenuOpen = false; langMenuOpen = false"
        />
    </Teleport>
</template>

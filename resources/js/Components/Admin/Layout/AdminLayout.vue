<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const page = usePage();
const sidebarOpen = ref(true);
const dropdownOpen = ref(false);

const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash as Record<string, string | null>);
const currentLocale = computed(() => page.props.locale as string);

const menuItems = [
    { name: 'sidebar.dashboard', route: 'admin.dashboard', icon: 'dashboard' },
    { name: 'sidebar.products', route: 'admin.products.index', icon: 'products' },
    { name: 'sidebar.categories', route: 'admin.categories.index', icon: 'categories' },
    { name: 'sidebar.orders', route: 'admin.orders.index', icon: 'orders' },
    { name: 'sidebar.stock', route: 'admin.stock.index', icon: 'stock' },
    { name: 'sidebar.promos', route: 'admin.promos.index', icon: 'promos' },
    { name: 'sidebar.reports', route: 'admin.reports.sales', icon: 'reports' },
    { name: 'sidebar.settings', route: 'admin.settings.general', icon: 'settings' },
    { name: 'sidebar.email_templates', route: 'admin.email-templates.index', icon: 'email' },
    { name: 'sidebar.payment_gateways', route: 'admin.payment-gateways.index', icon: 'payment' },
    { name: 'sidebar.pages', route: 'admin.pages.index', icon: 'pages' },
    { name: 'sidebar.users', route: 'admin.users.index', icon: 'users' },
];

function switchLocale(locale: string) {
    router.post(route('locale.switch', { locale }), {}, { preserveState: true });
}

function isActive(routeName: string): boolean {
    const currentRoute = route().current() as string;
    return currentRoute?.startsWith(routeName.replace('.index', '')) ?? false;
}
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-gray-100">
        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="fixed left-0 top-0 z-40 flex h-screen flex-col overflow-y-auto bg-[#1c2434] text-white transition-all duration-300"
        >
            <div class="flex items-center justify-between px-6 py-5">
                <Link :href="route('admin.dashboard')" class="text-xl font-bold" v-if="sidebarOpen">
                    Admin
                </Link>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 px-3 py-4">
                <Link
                    v-for="item in menuItems"
                    :key="item.route"
                    :href="route(item.route)"
                    :class="[
                        isActive(item.route)
                            ? 'bg-[#333a48] text-white'
                            : 'text-gray-400 hover:bg-[#333a48] hover:text-white',
                        'flex items-center rounded-lg px-4 py-2.5 text-sm font-medium transition-colors'
                    ]"
                >
                    <span class="flex-1" v-if="sidebarOpen">{{ t(item.name) }}</span>
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="flex flex-1 flex-col transition-all duration-300">
            <!-- Header -->
            <header class="sticky top-0 z-30 flex items-center justify-between border-b bg-white px-6 py-4 shadow-sm">
                <div></div>
                <div class="flex items-center gap-4">
                    <!-- Locale Switcher -->
                    <select
                        :value="currentLocale"
                        @change="switchLocale(($event.target as HTMLSelectElement).value)"
                        class="rounded border-gray-300 text-sm"
                    >
                        <option value="en">EN</option>
                        <option value="id">ID</option>
                    </select>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 text-sm">
                            <span class="hidden sm:block">{{ user?.name }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 rounded-lg border bg-white py-2 shadow-lg">
                            <Link :href="route('logout')" method="post" as="button" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                {{ t('actions.logout') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="flash?.success" class="mx-6 mt-4 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                {{ flash.success }}
            </div>
            <div v-if="flash?.error" class="mx-6 mt-4 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800">
                {{ flash.error }}
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

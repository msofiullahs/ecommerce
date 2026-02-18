<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    image_url: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Category {
    id: number;
    name: string;
    slug: string;
    products_count: number;
}

interface Filters {
    search?: string;
    category?: string;
    sort?: string;
}

const props = defineProps<{
    products: {
        data: Product[];
        links: PaginationLink[];
        current_page: number;
        last_page: number;
        total: number;
    };
    categories: Category[];
    filters: Filters;
}>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const selectedSort = ref(props.filters.sort || '');

let searchTimeout: ReturnType<typeof setTimeout>;

function applyFilters() {
    const params: Record<string, string> = {};
    if (search.value) params.search = search.value;
    if (selectedCategory.value) params.category = selectedCategory.value;
    if (selectedSort.value) params.sort = selectedSort.value;

    router.get(route('products.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
}

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
});

watch([selectedCategory, selectedSort], () => {
    applyFilters();
});

function formatCurrency(value: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
}
</script>

<template>
    <Head :title="t('products.title')" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">{{ t('products.title') }}</h1>

        <div class="lg:flex lg:gap-8">
            <!-- Sidebar Filters -->
            <aside class="mb-6 w-full shrink-0 lg:mb-0 lg:w-64">
                <div class="rounded-lg border bg-white p-4 shadow-sm">
                    <!-- Search -->
                    <div class="mb-6">
                        <input
                            v-model="search"
                            type="text"
                            :placeholder="t('products.search')"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">{{ t('home.categories') }}</h3>
                        <ul class="space-y-2">
                            <li>
                                <button
                                    @click="selectedCategory = ''"
                                    class="block w-full text-left text-sm transition hover:text-indigo-600"
                                    :class="selectedCategory === '' ? 'font-semibold text-indigo-600' : 'text-gray-600'"
                                >
                                    {{ t('products.all_categories') }}
                                </button>
                            </li>
                            <li v-for="category in categories" :key="category.id">
                                <button
                                    @click="selectedCategory = category.slug"
                                    class="block w-full text-left text-sm transition hover:text-indigo-600"
                                    :class="selectedCategory === category.slug ? 'font-semibold text-indigo-600' : 'text-gray-600'"
                                >
                                    {{ category.name }}
                                    <span class="text-gray-400">({{ category.products_count }})</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Product Grid -->
            <div class="flex-1">
                <!-- Sort Bar -->
                <div class="mb-6 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        {{ products.total }} {{ t('nav.products').toLowerCase() }}
                    </p>
                    <select
                        v-model="selectedSort"
                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">{{ t('products.sort_by') }}</option>
                        <option value="newest">{{ t('products.newest') }}</option>
                        <option value="price_asc">{{ t('products.price_low') }}</option>
                        <option value="price_desc">{{ t('products.price_high') }}</option>
                    </select>
                </div>

                <!-- Products -->
                <div v-if="products.data.length > 0" class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    <Link
                        v-for="product in products.data"
                        :key="product.id"
                        :href="route('products.show', product.slug)"
                        class="group overflow-hidden rounded-lg border bg-white shadow-sm transition hover:shadow-md"
                    >
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img
                                :src="product.image_url"
                                :alt="product.name"
                                class="h-full w-full object-cover transition group-hover:scale-105"
                            />
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600">
                                {{ product.name }}
                            </h3>
                            <p class="mt-1 text-lg font-bold text-indigo-600">
                                {{ formatCurrency(product.price) }}
                            </p>
                        </div>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-else class="rounded-lg border bg-white py-16 text-center">
                    <svg class="mx-auto mb-4 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-gray-500">{{ t('products.no_products') }}</p>
                </div>

                <!-- Pagination -->
                <nav v-if="products.links.length > 3" class="mt-8 flex items-center justify-center gap-1">
                    <template v-for="link in products.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'rounded-lg px-3 py-2 text-sm',
                                link.active
                                    ? 'bg-indigo-600 text-white'
                                    : 'border bg-white text-gray-700 hover:bg-gray-100',
                            ]"
                            v-html="link.label"
                            preserve-scroll
                        />
                        <span
                            v-else
                            class="rounded-lg px-3 py-2 text-sm text-gray-400"
                            v-html="link.label"
                        />
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>

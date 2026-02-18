<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface Banner {
    id: number;
    title: string;
    subtitle: string;
    image_url: string;
    link_url: string | null;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    image_url: string;
}

interface Category {
    id: number;
    name: string;
    slug: string;
    image_url: string | null;
    products_count: number;
}

defineProps<{
    banners: Banner[];
    featuredProducts: Product[];
    categories: Category[];
}>();

function formatCurrency(value: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
}
</script>

<template>
    <Head :title="t('nav.home')" />

    <div>
        <!-- Hero Banner -->
        <section v-if="banners.length > 0" class="relative bg-gray-900">
            <div class="relative h-[400px] overflow-hidden md:h-[500px]">
                <div
                    v-for="(banner, index) in banners"
                    :key="banner.id"
                    :class="{ hidden: index !== 0 }"
                    class="absolute inset-0"
                >
                    <img
                        :src="banner.image_url"
                        :alt="banner.title"
                        class="h-full w-full object-cover opacity-60"
                    />
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <h1 class="mb-4 text-4xl font-bold text-white md:text-5xl">
                                {{ banner.title }}
                            </h1>
                            <p class="mb-6 text-lg text-gray-200 md:text-xl">
                                {{ banner.subtitle }}
                            </p>
                            <Link
                                v-if="banner.link_url"
                                :href="banner.link_url"
                                class="inline-block rounded-lg bg-indigo-600 px-8 py-3 text-lg font-semibold text-white transition hover:bg-indigo-700"
                            >
                                {{ t('home.shop_now') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Default Hero if no banners -->
        <section v-else class="bg-gradient-to-r from-indigo-600 to-purple-700 py-20">
            <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                <h1 class="mb-4 text-4xl font-bold text-white md:text-5xl">
                    {{ t('home.hero_title') }}
                </h1>
                <p class="mb-8 text-lg text-indigo-100 md:text-xl">
                    {{ t('home.hero_subtitle') }}
                </p>
                <Link
                    :href="route('products.index')"
                    class="inline-block rounded-lg bg-white px-8 py-3 text-lg font-semibold text-indigo-600 transition hover:bg-gray-100"
                >
                    {{ t('home.shop_now') }}
                </Link>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-16" v-if="featuredProducts.length > 0">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">{{ t('home.featured_products') }}</h2>
                    <Link
                        :href="route('products.index')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                    >
                        {{ t('home.view_all') }} &rarr;
                    </Link>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <Link
                        v-for="product in featuredProducts"
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
            </div>
        </section>

        <!-- Categories -->
        <section class="bg-gray-100 py-16" v-if="categories.length > 0">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-8 text-center text-2xl font-bold text-gray-900">{{ t('home.categories') }}</h2>
                <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('products.index', { category: category.slug })"
                        class="group relative overflow-hidden rounded-lg bg-white shadow-sm transition hover:shadow-md"
                    >
                        <div class="aspect-[4/3] overflow-hidden bg-gray-200">
                            <img
                                v-if="category.image_url"
                                :src="category.image_url"
                                :alt="category.name"
                                class="h-full w-full object-cover transition group-hover:scale-105"
                            />
                            <div v-else class="flex h-full items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                                <svg class="h-12 w-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-medium text-gray-900 group-hover:text-indigo-600">
                                {{ category.name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ category.products_count }} {{ t('nav.products').toLowerCase() }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>

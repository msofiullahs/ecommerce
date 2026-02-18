<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FrontendLayout from '@/Components/Frontend/Layout/FrontendLayout.vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: FrontendLayout });

const { t } = useI18n();

interface ProductImage {
    id: number;
    image_url: string;
}

interface Variant {
    id: number;
    name: string;
    price: number;
    stock: number;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    stock: number;
    images: ProductImage[];
    image_url: string;
    variants: Variant[];
}

interface RelatedProduct {
    id: number;
    name: string;
    slug: string;
    price: number;
    image_url: string;
}

const props = defineProps<{
    product: Product;
    relatedProducts: RelatedProduct[];
}>();

const selectedImageIndex = ref(0);
const selectedVariant = ref<Variant | null>(
    props.product.variants.length > 0 ? props.product.variants[0] : null
);
const quantity = ref(1);

const allImages = computed(() => {
    if (props.product.images && props.product.images.length > 0) {
        return props.product.images;
    }
    return [{ id: 0, image_url: props.product.image_url }];
});

const currentPrice = computed(() => {
    return selectedVariant.value ? selectedVariant.value.price : props.product.price;
});

const currentStock = computed(() => {
    return selectedVariant.value ? selectedVariant.value.stock : props.product.stock;
});

const isInStock = computed(() => currentStock.value > 0);

const form = useForm({
    product_id: props.product.id,
    variant_id: null as number | null,
    quantity: 1,
});

function selectVariant(variant: Variant) {
    selectedVariant.value = variant;
    form.variant_id = variant.id;
}

function addToCart() {
    form.quantity = quantity.value;
    form.variant_id = selectedVariant.value?.id ?? null;
    form.post(route('cart.add'), {
        preserveScroll: true,
    });
}

function formatCurrency(value: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
}
</script>

<template>
    <Head :title="product.name" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li>
                    <Link :href="route('home')" class="hover:text-indigo-600">{{ t('nav.home') }}</Link>
                </li>
                <li>/</li>
                <li>
                    <Link :href="route('products.index')" class="hover:text-indigo-600">{{ t('nav.products') }}</Link>
                </li>
                <li>/</li>
                <li class="text-gray-900">{{ product.name }}</li>
            </ol>
        </nav>

        <!-- Product Detail -->
        <div class="lg:flex lg:gap-12">
            <!-- Image Gallery -->
            <div class="mb-8 lg:mb-0 lg:w-1/2">
                <div class="mb-4 aspect-square overflow-hidden rounded-lg bg-gray-100">
                    <img
                        :src="allImages[selectedImageIndex].image_url"
                        :alt="product.name"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div v-if="allImages.length > 1" class="flex gap-3 overflow-x-auto">
                    <button
                        v-for="(image, index) in allImages"
                        :key="image.id"
                        @click="selectedImageIndex = index"
                        :class="[
                            'h-20 w-20 shrink-0 overflow-hidden rounded-lg border-2 transition',
                            selectedImageIndex === index ? 'border-indigo-600' : 'border-transparent hover:border-gray-300',
                        ]"
                    >
                        <img :src="image.image_url" :alt="product.name" class="h-full w-full object-cover" />
                    </button>
                </div>
            </div>

            <!-- Product Info -->
            <div class="lg:w-1/2">
                <h1 class="mb-4 text-3xl font-bold text-gray-900">{{ product.name }}</h1>

                <p class="mb-6 text-3xl font-bold text-indigo-600">
                    {{ formatCurrency(currentPrice) }}
                </p>

                <!-- Stock Status -->
                <div class="mb-6">
                    <span
                        v-if="isInStock"
                        class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800"
                    >
                        {{ t('products.in_stock') }}
                    </span>
                    <span
                        v-else
                        class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800"
                    >
                        {{ t('products.out_of_stock') }}
                    </span>
                </div>

                <!-- Variant Selector -->
                <div v-if="product.variants.length > 0" class="mb-6">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">{{ t('products.select_variant') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="variant in product.variants"
                            :key="variant.id"
                            @click="selectVariant(variant)"
                            :class="[
                                'rounded-lg border px-4 py-2 text-sm font-medium transition',
                                selectedVariant?.id === variant.id
                                    ? 'border-indigo-600 bg-indigo-50 text-indigo-700'
                                    : 'border-gray-300 text-gray-700 hover:border-gray-400',
                                variant.stock === 0 ? 'cursor-not-allowed opacity-50' : '',
                            ]"
                            :disabled="variant.stock === 0"
                        >
                            {{ variant.name }}
                        </button>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-6">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">{{ t('products.quantity') }}</h3>
                    <div class="flex items-center gap-3">
                        <button
                            @click="quantity = Math.max(1, quantity - 1)"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border text-gray-600 hover:bg-gray-100"
                        >
                            -
                        </button>
                        <input
                            v-model.number="quantity"
                            type="number"
                            min="1"
                            :max="currentStock"
                            class="h-10 w-20 rounded-lg border-gray-300 text-center text-sm"
                        />
                        <button
                            @click="quantity = Math.min(currentStock, quantity + 1)"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border text-gray-600 hover:bg-gray-100"
                        >
                            +
                        </button>
                    </div>
                </div>

                <!-- Add to Cart -->
                <button
                    @click="addToCart"
                    :disabled="!isInStock || form.processing"
                    class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-lg font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span v-if="form.processing">{{ t('common.loading') }}</span>
                    <span v-else>{{ t('products.add_to_cart') }}</span>
                </button>

                <!-- Description -->
                <div class="mt-8 border-t pt-8">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">{{ t('products.description') }}</h2>
                    <div class="prose max-w-none text-gray-600" v-html="product.description" />
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <section v-if="relatedProducts.length > 0" class="mt-16">
            <h2 class="mb-8 text-2xl font-bold text-gray-900">{{ t('products.related') }}</h2>
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <Link
                    v-for="related in relatedProducts"
                    :key="related.id"
                    :href="route('products.show', related.slug)"
                    class="group overflow-hidden rounded-lg border bg-white shadow-sm transition hover:shadow-md"
                >
                    <div class="aspect-square overflow-hidden bg-gray-100">
                        <img
                            :src="related.image_url"
                            :alt="related.name"
                            class="h-full w-full object-cover transition group-hover:scale-105"
                        />
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600">
                            {{ related.name }}
                        </h3>
                        <p class="mt-1 text-lg font-bold text-indigo-600">
                            {{ formatCurrency(related.price) }}
                        </p>
                    </div>
                </Link>
            </div>
        </section>
    </div>
</template>

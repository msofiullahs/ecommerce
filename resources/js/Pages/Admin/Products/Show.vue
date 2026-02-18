<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
defineProps<{ product: any }>();
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">{{ product.name }}</h1>
            <Link :href="route('admin.products.edit', product.id)" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Edit</Link>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <Card>
                <h3 class="mb-3 font-semibold">Details</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">SKU</dt><dd>{{ product.sku }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Price</dt><dd>{{ product.price }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Category</dt><dd>{{ product.category?.name || '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><Badge :color="product.is_active ? 'green' : 'red'">{{ product.is_active ? 'Active' : 'Inactive' }}</Badge></dd></div>
                </dl>
            </Card>
            <Card>
                <h3 class="mb-3 font-semibold">Variants</h3>
                <div v-for="variant in product.variants" :key="variant.id" class="mb-2 flex items-center justify-between rounded border p-3 text-sm">
                    <div>
                        <p class="font-medium">{{ variant.name }} ({{ variant.sku }})</p>
                        <p class="text-gray-500">Stock: {{ variant.stock }}</p>
                    </div>
                    <Badge :color="variant.stock > variant.low_stock_threshold ? 'green' : variant.stock > 0 ? 'yellow' : 'red'">
                        {{ variant.stock > variant.low_stock_threshold ? 'In Stock' : variant.stock > 0 ? 'Low' : 'Out' }}
                    </Badge>
                </div>
            </Card>
        </div>
    </div>
</template>

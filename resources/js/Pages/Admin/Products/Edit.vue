<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });

const props = defineProps<{ product: any; categories: any[]; locales: string[] }>();

const form = useForm({
    name: props.product.name || { en: '', id: '' },
    description: props.product.description || { en: '', id: '' },
    short_description: props.product.short_description || { en: '', id: '' },
    slug: props.product.slug,
    sku: props.product.sku,
    price: props.product.price,
    compare_price: props.product.compare_price,
    cost_price: props.product.cost_price,
    category_id: props.product.category_id,
    is_active: props.product.is_active,
    is_featured: props.product.is_featured,
    weight: props.product.weight,
    meta: props.product.meta || { en: '', id: '' },
    images: [] as File[],
    remove_images: [] as number[],
    variants: props.product.variants || [],
});

function submit() {
    form.post(route('admin.products.update', props.product.id), { forceFormData: true, _method: 'PUT' } as any);
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Edit Product</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <Card>
                <div class="grid gap-4 md:grid-cols-2">
                    <TranslatableInput v-model="form.name" label="Product Name" required />
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Slug</label>
                        <input v-model="form.slug" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">SKU</label>
                        <input v-model="form.sku" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Price</label>
                        <input v-model.number="form.price" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Category</label>
                        <select v-model="form.category_id" class="w-full rounded-lg border-gray-300">
                            <option :value="null">No Category</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <TranslatableInput v-model="form.description" label="Description" type="textarea" />
                </div>
                <div class="mt-4 flex gap-6">
                    <label class="flex items-center gap-2">
                        <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-blue-600" />
                        <span class="text-sm">Active</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input v-model="form.is_featured" type="checkbox" class="rounded border-gray-300 text-blue-600" />
                        <span class="text-sm">Featured</span>
                    </label>
                </div>
            </Card>

            <div class="flex justify-end gap-3">
                <Button variant="secondary" type="button" @click="$inertia.visit(route('admin.products.index'))">Cancel</Button>
                <Button type="submit" :loading="form.processing">Save Changes</Button>
            </div>
        </form>
    </div>
</template>

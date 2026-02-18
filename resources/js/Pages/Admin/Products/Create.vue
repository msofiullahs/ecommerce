<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: AdminLayout });
const { t } = useI18n();

const props = defineProps<{
    categories: Array<{ id: number; name: string }>;
    locales: string[];
}>();

const form = useForm({
    name: { en: '', id: '' },
    description: { en: '', id: '' },
    short_description: { en: '', id: '' },
    slug: '',
    sku: '',
    price: 0,
    compare_price: null as number | null,
    cost_price: null as number | null,
    category_id: null as number | null,
    is_active: true,
    is_featured: false,
    weight: null as number | null,
    meta: { en: '', id: '' },
    images: [] as File[],
    variants: [
        { name: { en: '', id: '' }, sku: '', price: null as number | null, stock: 0, low_stock_threshold: 5, attributes: {}, is_active: true },
    ],
});

function generateSlug() {
    form.slug = (form.name.en || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

function addVariant() {
    form.variants.push({ name: { en: '', id: '' }, sku: '', price: null, stock: 0, low_stock_threshold: 5, attributes: {}, is_active: true });
}

function removeVariant(index: number) {
    form.variants.splice(index, 1);
}

function submit() {
    form.post(route('admin.products.store'), { forceFormData: true });
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">{{ t('products.add_new') }}</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <Card>
                <h3 class="mb-4 text-lg font-semibold">Basic Information</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <TranslatableInput v-model="form.name" label="Product Name" required :error="form.errors['name.en']" />
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Slug *</label>
                        <div class="flex gap-2">
                            <input v-model="form.slug" type="text" class="flex-1 rounded-lg border-gray-300" />
                            <Button type="button" variant="secondary" size="sm" @click="generateSlug">Generate</Button>
                        </div>
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">SKU *</label>
                        <input v-model="form.sku" type="text" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Price *</label>
                        <input v-model.number="form.price" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Compare Price</label>
                        <input v-model.number="form.compare_price" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
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

            <!-- Variants -->
            <Card>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Variants</h3>
                    <Button type="button" variant="secondary" size="sm" @click="addVariant">Add Variant</Button>
                </div>
                <div v-for="(variant, index) in form.variants" :key="index" class="mb-4 rounded-lg border p-4">
                    <div class="mb-2 flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Variant {{ index + 1 }}</span>
                        <button v-if="form.variants.length > 1" type="button" @click="removeVariant(index)" class="text-sm text-red-600">Remove</button>
                    </div>
                    <div class="grid gap-3 md:grid-cols-4">
                        <TranslatableInput v-model="variant.name" label="Variant Name" />
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">SKU *</label>
                            <input v-model="variant.sku" type="text" class="w-full rounded-lg border-gray-300" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Stock *</label>
                            <input v-model.number="variant.stock" type="number" class="w-full rounded-lg border-gray-300" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Low Stock Threshold</label>
                            <input v-model.number="variant.low_stock_threshold" type="number" class="w-full rounded-lg border-gray-300" />
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Images -->
            <Card>
                <h3 class="mb-4 text-lg font-semibold">Images</h3>
                <input type="file" multiple accept="image/*" @change="(e: any) => form.images = Array.from(e.target.files)" class="text-sm" />
            </Card>

            <div class="flex justify-end gap-3">
                <Button variant="secondary" type="button" @click="$inertia.visit(route('admin.products.index'))">Cancel</Button>
                <Button type="submit" :loading="form.processing" :disabled="form.processing">{{ t('actions.save') }}</Button>
            </div>
        </form>
    </div>
</template>

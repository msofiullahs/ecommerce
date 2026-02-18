<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Modal from '@/Components/Admin/UI/Modal.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ banners: any[] }>();

const showModal = ref(false);
const editingBanner = ref<any>(null);

const form = useForm({
    title: { en: '', id: '' },
    subtitle: { en: '', id: '' },
    link: '',
    sort_order: 0,
    is_active: true,
    image: null as File | null,
});

function openCreate() {
    editingBanner.value = null;
    form.reset();
    showModal.value = true;
}

function openEdit(banner: any) {
    editingBanner.value = banner;
    form.title = banner.title || { en: '', id: '' };
    form.subtitle = banner.subtitle || { en: '', id: '' };
    form.link = banner.link || '';
    form.sort_order = banner.sort_order || 0;
    form.is_active = banner.is_active;
    form.image = null;
    showModal.value = true;
}

function submit() {
    if (editingBanner.value) {
        form.post(route('admin.settings.banners.update', editingBanner.value.id), {
            forceFormData: true,
            onSuccess: () => { showModal.value = false; },
        });
    } else {
        form.post(route('admin.settings.banners.store'), {
            forceFormData: true,
            onSuccess: () => { showModal.value = false; },
        });
    }
}

function deleteBanner(id: number) {
    if (confirm('Are you sure?')) router.delete(route('admin.settings.banners.destroy', id));
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Banners</h1>
            <Button @click="openCreate">Add Banner</Button>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <Card v-for="banner in banners" :key="banner.id">
                <div class="mb-3">
                    <img v-if="banner.image_url" :src="banner.image_url" class="h-32 w-full rounded-lg object-cover" alt="" />
                    <div v-else class="flex h-32 items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Image</div>
                </div>
                <h3 class="font-medium">{{ banner.title?.en || banner.title || 'Untitled' }}</h3>
                <p class="mb-2 text-sm text-gray-500">{{ banner.subtitle?.en || banner.subtitle || '' }}</p>
                <div class="flex items-center justify-between">
                    <Badge :color="banner.is_active ? 'green' : 'red'">{{ banner.is_active ? 'Active' : 'Inactive' }}</Badge>
                    <div class="flex gap-2 text-sm">
                        <button @click="openEdit(banner)" class="text-blue-600 hover:underline">Edit</button>
                        <button @click="deleteBanner(banner.id)" class="text-red-600 hover:underline">Delete</button>
                    </div>
                </div>
            </Card>
        </div>

        <Modal :show="showModal" @close="showModal = false" max-width="lg">
            <h3 class="mb-4 text-lg font-semibold">{{ editingBanner ? 'Edit' : 'Add' }} Banner</h3>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium">Title (EN)</label>
                    <input v-model="form.title.en" type="text" class="w-full rounded-lg border-gray-300" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Subtitle (EN)</label>
                    <input v-model="form.subtitle.en" type="text" class="w-full rounded-lg border-gray-300" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Link</label>
                    <input v-model="form.link" type="text" class="w-full rounded-lg border-gray-300" placeholder="/products" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Image</label>
                    <input type="file" accept="image/*" @change="(e: any) => form.image = e.target.files[0]" class="text-sm" />
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Sort Order</label>
                        <input v-model.number="form.sort_order" type="number" class="w-full rounded-lg border-gray-300" />
                    </div>
                    <label class="flex items-center gap-2 self-end">
                        <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-blue-600" />
                        <span class="text-sm">Active</span>
                    </label>
                </div>
                <div class="flex justify-end gap-2">
                    <Button variant="secondary" @click="showModal = false">Cancel</Button>
                    <Button type="submit" :loading="form.processing">Save</Button>
                </div>
            </form>
        </Modal>
    </div>
</template>

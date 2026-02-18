<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ gateway: any; configFields: Array<{ key: string; label: string; type: string; required?: boolean }> }>();

const formData: Record<string, any> = {};
for (const field of props.configFields) {
    formData[field.key] = props.gateway.config?.[field.key] || '';
}

const form = useForm(formData);

function submit() {
    form.put(route('admin.payment-gateways.update-config', props.gateway.id));
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Configure {{ gateway.name }}</h1>
        <form @submit.prevent="submit">
            <Card class="space-y-4">
                <div v-for="field in configFields" :key="field.key">
                    <label class="mb-1 block text-sm font-medium">
                        {{ field.label }} <span v-if="field.required" class="text-red-500">*</span>
                    </label>
                    <input
                        v-if="field.type === 'text' || field.type === 'password'"
                        v-model="form[field.key]"
                        :type="field.type"
                        class="w-full rounded-lg border-gray-300"
                    />
                    <select v-else-if="field.type === 'select'" v-model="form[field.key]" class="w-full rounded-lg border-gray-300">
                        <option value="sandbox">Sandbox</option>
                        <option value="production">Production</option>
                    </select>
                    <textarea v-else-if="field.type === 'textarea'" v-model="form[field.key]" class="w-full rounded-lg border-gray-300" rows="4" />
                    <p v-if="form.errors[field.key]" class="mt-1 text-sm text-red-600">{{ form.errors[field.key] }}</p>
                </div>
            </Card>
            <div class="mt-4 flex justify-end gap-3">
                <Button variant="secondary" type="button" @click="router.visit(route('admin.payment-gateways.index'))">Cancel</Button>
                <Button type="submit" :loading="form.processing">Save Configuration</Button>
            </div>
        </form>
    </div>
</template>

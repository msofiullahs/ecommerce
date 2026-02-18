<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import TranslatableInput from '@/Components/Admin/Forms/TranslatableInput.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ template: any; locales: string[]; availableVariables: string[] }>();

const form = useForm({
    name: props.template.name,
    subject: props.template.subject || { en: '', id: '' },
    body_html: props.template.body_html || { en: '', id: '' },
    is_active: props.template.is_active,
});

const activeLocale = ref('en');

function submit() { form.put(route('admin.email-templates.update', props.template.id)); }

function sendTest() {
    router.post(route('admin.email-templates.send-test', props.template.id));
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Edit Email Template: {{ template.name }}</h1>
            <Button variant="secondary" @click="sendTest">Send Test Email</Button>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            <Card>
                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium">Template Name</label>
                    <input v-model="form.name" type="text" class="w-full rounded-lg border-gray-300" disabled />
                </div>
                <TranslatableInput v-model="form.subject" label="Subject" required />
            </Card>

            <Card>
                <h3 class="mb-4 text-lg font-semibold">Email Body</h3>
                <div class="mb-2 flex gap-1">
                    <button v-for="locale in props.locales" :key="locale" type="button"
                        @click="activeLocale = locale"
                        :class="[activeLocale === locale ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600', 'rounded px-3 py-1 text-xs font-medium uppercase']">
                        {{ locale }}
                    </button>
                </div>
                <template v-for="locale in props.locales" :key="locale">
                    <textarea
                        v-if="activeLocale === locale"
                        v-model="form.body_html[locale]"
                        class="w-full rounded-lg border-gray-300 font-mono text-sm"
                        rows="16"
                        :placeholder="`HTML body (${locale.toUpperCase()})`"
                    />
                </template>
            </Card>

            <Card>
                <h3 class="mb-3 text-sm font-semibold">Available Variables</h3>
                <div class="flex flex-wrap gap-2">
                    <code v-for="v in availableVariables" :key="v" class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700" v-text="'{{' + v + '}}'"></code>
                </div>
            </Card>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-blue-600" />
                    <span class="text-sm">Active</span>
                </label>
                <div class="flex gap-3">
                    <Button variant="secondary" type="button" @click="router.visit(route('admin.email-templates.index'))">Cancel</Button>
                    <Button type="submit" :loading="form.processing">Save</Button>
                </div>
            </div>
        </form>
    </div>
</template>

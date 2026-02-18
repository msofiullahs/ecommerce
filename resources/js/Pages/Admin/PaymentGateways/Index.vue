<script setup lang="ts">
import AdminLayout from '@/Components/Admin/Layout/AdminLayout.vue';
import Card from '@/Components/Admin/UI/Card.vue';
import Badge from '@/Components/Admin/UI/Badge.vue';
import Button from '@/Components/Admin/UI/Button.vue';
import { Link, router } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout });
const props = defineProps<{ gateways: any[] }>();

function toggleGateway(gateway: any) {
    router.put(route('admin.payment-gateways.toggle', gateway.id));
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Payment Gateways</h1>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <Card v-for="gw in gateways" :key="gw.id">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ gw.name }}</h3>
                    <Badge :color="gw.is_active ? 'green' : 'red'">{{ gw.is_active ? 'Active' : 'Inactive' }}</Badge>
                </div>
                <p class="mb-4 text-sm text-gray-500">{{ gw.description || 'No description' }}</p>
                <div class="flex gap-2">
                    <Link :href="route('admin.payment-gateways.configure', gw.id)">
                        <Button size="sm" variant="secondary">Configure</Button>
                    </Link>
                    <Button size="sm" :variant="gw.is_active ? 'danger' : 'success'" @click="toggleGateway(gw)">
                        {{ gw.is_active ? 'Disable' : 'Enable' }}
                    </Button>
                </div>
            </Card>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const props = defineProps<{
    labels: string[];
    datasets: Array<{
        label: string;
        data: number[];
        backgroundColor?: string;
    }>;
}>();

const chartData = computed(() => ({
    labels: props.labels,
    datasets: props.datasets.map(ds => ({
        ...ds,
        backgroundColor: ds.backgroundColor || '#3b82f6',
    })),
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: true } },
};
</script>

<template>
    <div class="h-[300px]">
        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>

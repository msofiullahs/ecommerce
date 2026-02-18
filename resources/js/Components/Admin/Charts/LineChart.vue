<script setup lang="ts">
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

const props = defineProps<{
    labels: string[];
    datasets: Array<{
        label: string;
        data: number[];
        borderColor?: string;
        backgroundColor?: string;
    }>;
    title?: string;
}>();

const chartData = computed(() => ({
    labels: props.labels,
    datasets: props.datasets.map(ds => ({
        ...ds,
        borderColor: ds.borderColor || '#3b82f6',
        backgroundColor: ds.backgroundColor || 'rgba(59, 130, 246, 0.1)',
        fill: true,
        tension: 0.4,
    })),
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: true } },
    scales: {
        y: { beginAtZero: true },
    },
};
</script>

<template>
    <div class="h-[300px]">
        <Line :data="chartData" :options="chartOptions" />
    </div>
</template>

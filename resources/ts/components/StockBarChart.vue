<script setup lang="ts">
import { Article } from "@/types";
import { onMounted, ref, watch } from "vue";

const props = defineProps<{
    articles: Article[];
}>();

const canvasRef = ref<HTMLCanvasElement | null>(null);

type ChartItem = {
    name: string;
    stock: number;
    color: string;
};

const chartData = ref<ChartItem[]>([]);

function generateRandomRGB(): string {
    const max = 255;

    const r = Math.floor(Math.random() * max);
    const g = Math.floor(Math.random() * max);
    const b = Math.floor(Math.random() * max);

    return `rgb(${r}, ${g}, ${b})`;
}

function buildChartData() {
    chartData.value = props.articles.map((article) => ({
        name: article.name,
        stock: article.stock ?? 0,
        color: generateRandomRGB(),
    }));
}

function getMaxStock(): number {
    return Math.max(...chartData.value.map((item) => item.stock), 1);
}

function drawChart() {
    const canvas = canvasRef.value;

    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    if (!ctx) return;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const padding = 40;
    const chartHeight = canvas.height - padding * 2;
    const chartWidth = canvas.width - padding * 2;

    const maxStock = getMaxStock();

    const barWidth = chartWidth / chartData.value.length;

    // ===== GRID =====
    const numLines = 5;

    ctx.strokeStyle = "#e5e7eb";
    ctx.fillStyle = "#6b7280";
    ctx.font = "12px sans-serif";

    for (let i = 0; i <= numLines; i++) {
        const y = padding + (chartHeight / numLines) * i;

        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(canvas.width - padding, y);
        ctx.stroke();

        const value = Math.round(maxStock - (maxStock / numLines) * i);

        ctx.fillText(String(value), 8, y + 4);
    }

    // ===== BARS =====
    chartData.value.forEach((item, index) => {
        const barHeight = (item.stock / maxStock) * chartHeight;

        const x = padding + index * barWidth + 10;

        const y = canvas.height - padding - barHeight;

        ctx.fillStyle = item.color;

        ctx.fillRect(x, y, barWidth - 20, barHeight);
    });
}

watch(
    () => props.articles,
    () => {
        buildChartData();
        drawChart();
    },
    { deep: true },
);

onMounted(() => {
    buildChartData();
    drawChart();
});
</script>

<template>
    <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
        <div class="mb-5">
            <h2 class="text-rojo-fuerte text-2xl font-bold">
                Stock de productos
            </h2>

            <p class="text-texto-secundario mt-1 text-sm">
                Visualización de stock actual por artículo
            </p>
        </div>

        <div class="overflow-x-auto">
            <canvas
                ref="canvasRef"
                width="900"
                height="450"
                class="w-full rounded-2xl bg-white"
            />
        </div>

        <!-- LEYENDA -->
        <div class="mt-6 flex flex-wrap gap-4">
            <div
                v-for="item in chartData"
                :key="item.name"
                class="flex items-center gap-2"
            >
                <div
                    class="h-4 w-4 rounded-sm"
                    :style="{ backgroundColor: item.color }"
                />

                <span class="text-sm text-slate-600">
                    {{ item.name }}
                    ({{ item.stock }})
                </span>
            </div>
        </div>
    </div>
</template>

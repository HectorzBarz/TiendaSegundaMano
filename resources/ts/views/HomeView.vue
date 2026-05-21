<script setup lang="ts">
import { onMounted, ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { Carousel, Slide, Navigation, Pagination } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

import CategoryItemCard from "@/components/CategoryItemCard.vue";
import { Category } from "@/types";

import slide1 from "@/assets/slide1.webp";
import slide2 from "@/assets/slide2.webp";
import slide3 from "@/assets/slide3.webp";

const categories = ref<Category[]>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);

const router = useRouter();

const slides = [
    { id: 1, img: slide1 },
    { id: 2, img: slide2 },
    { id: 3, img: slide3 },
];

const config = {
    itemsToShow: 1,
    gap: 5,
    autoplay: 4000,
    wrapAround: true,
    pauseAutoplayOnHover: true,
};

// 👉 navegación con filtro por categoría
function selectCategory(id: number) {
    router.push({
        name: "articles",
        query: { category: id.toString() },
    });
}

onMounted(async () => {
    try {
        const response = await axios.get(
            "http://localhost:8000/api/categories",
            {
                params: { limit: 8 },
            },
        );

        categories.value = response.data;
    } catch (e: any) {
        if (e.response) {
            console.error("Error de servidor:", e.response.status);
            console.error("Data:", e.response.data);
            error.value = "Error al cargar categorías";
        } else {
            console.error("Error de red:", e.message);
            error.value = "Error de conexión";
        }
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <div class="bg-fondo flex h-full flex-col gap-5">
        <!-- Hero -->
        <div class="py-6 text-center">
            <h1 class="text-surface-800 text-3xl font-bold">
                Una segunda vida para cada objeto
            </h1>
            <p class="text-surface-600 mt-2">
                Compra fácil, reutiliza sin complicaciones.
            </p>
        </div>

        <!-- Carousel -->
        <div
            class="border-borde mx-5 overflow-hidden rounded-2xl border md:mx-20"
        >
            <Carousel v-bind="config" :wrap-around="true">
                <Slide v-for="slide in slides" :key="slide.id">
                    <img
                        :src="slide.img"
                        class="h-96 w-full rounded-2xl object-cover"
                    />
                </Slide>
                <template #addons><Navigation /><Pagination /></template>
            </Carousel>
        </div>

        <!-- Categories -->
        <div class="m-5 text-center">
            <div v-if="isLoading" class="text-surface-500 py-10">
                Cargando categorías...
            </div>

            <div v-else-if="error" class="py-10 text-red-500">
                {{ error }}
            </div>

            <div
                v-else
                class="grid h-full gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <div v-for="category in categories" :key="category.id">
                    <div
                        @click="selectCategory(category.id)"
                        class="cursor-pointer"
                    >
                        <CategoryItemCard
                            :category="category"
                            class="bg-card border-borde transition hover:border-blue-400"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import CategoryItemCard from "@/components/CategoryItemCard.vue";
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { useRouter } from "vue-router";
import slide1 from "@/assets/slide1.webp";
import slide2 from "@/assets/slide2.webp";
import slide3 from "@/assets/slide3.webp";

import img1 from "@/assets/example/juguetes.webp";
import img2 from "@/assets/example/informatica.png";
import img3 from "@/assets/example/dados.png";
import img4 from "@/assets/example/nevera.svg";
import img5 from "@/assets/example/mesa.svg";
import img6 from "@/assets/example/patinete.svg";
import img7 from "@/assets/example/camiseta.svg";
import img8 from "@/assets/example/videojuegos.svg";

import { Carousel, Slide, Navigation, Pagination } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

const categories = [
    { id: 1, img: img1, name: "Muebles" },
    { id: 2, img: img2, name: "Consolas" },
    { id: 3, img: img3, name: "Cubertería" },
    { id: 4, img: img4, name: "Deportes" },
    { id: 5, img: img5, name: "Informática" },
    { id: 6, img: img6, name: "Juegos de mesa" },
    { id: 7, img: img7, name: "Patinetes" },
    { id: 8, img: img8, name: "Ropa" },
];

const slides = [
    { id: 1, img: slide1 },
    { id: 2, img: slide2 },
    { id: 3, img: slide3 },
];

const store = useArticleFiltersStore();
const router = useRouter();

const config = {
    itemsToShow: 1,
    gap: 5,
    autoplay: 4000,
    wrapAround: true,
    pauseAutoplayOnHover: true,
};

function selectCategory(id: number) {
    store.setCategory(id);
    router.push("/articles");
}
</script>

<template>
    <div class="bg-fondo flex h-full flex-col gap-5">
        <!-- Hero slogan -->
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
                    <div class="relative h-96 w-full">
                        <img
                            :src="slide.img"
                            class="h-full w-full rounded-2xl object-cover"
                        />
                    </div>
                </Slide>

                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>
        </div>

        <!-- Category Selector -->
        <div
            class="m-5 grid h-full text-center align-middle md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
        >
            <div v-for="category in categories" :key="category.id">
                <RouterLink to="/articles" @click="selectCategory(category.id)">
                    <CategoryItemCard
                        :category="category"
                        class="bg-card border-borde"
                    />
                </RouterLink>
            </div>
        </div>
    </div>
</template>

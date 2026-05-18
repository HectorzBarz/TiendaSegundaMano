<script setup lang="ts">
import Button from "@volt/Button.vue";
import hampter from "/storage/app/public/img/hampter.jpg";
import { Carousel, Slide, Navigation, Pagination } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

import img1 from "@/assets/slide1.webp";
import img2 from "@/assets/slide2.webp";
import img3 from "@/assets/slide3.webp";

import { useRouter } from "vue-router";
import { Article } from "@/types";
import { useCartStore } from "@/stores/cart";

const cart = useCartStore();

const article = <Article>{
    id: 1,
    img: hampter,
    description:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin volutpat elit risus, semper tincidunt purus vestibulum ut.",
    oldPrice: 1,
    price: 0.1,
    name: "Hámster adorable",
    onSale: true,
    itemState: 1,
    categoryId: 1,
    product_avg: 3.2,
    product_count: 3,
};

const hasStock = (article.stock ?? 0) > 0;

const slides = [
    { id: 1, img: img1, title: "Oferta 1" },
    { id: 2, img: img2, title: "Oferta 2" },
    { id: 3, img: img3, title: "Oferta 3" },
    { id: 3, img: hampter, title: "Oferta 3" },
];

const config = {
    itemsToShow: 1,
    wrapAround: true,
    autoplay: 0, // 👈 en detalle normalmente NO autoplay
    pauseAutoplayOnHover: true,
};

const router = useRouter();

const goToQuery = () => {
    router.push({
        name: "article-query",
        params: {
            id: article.id,
        },
    });
};
</script>

<template>
    <main class="bg-fondo mx-auto max-w-6xl p-10">
        <div class="grid gap-10 md:grid-cols-2">
            <!-- IMAGEN -->
            <div
                class="bg-card border-borde overflow-hidden rounded-2xl border shadow-sm"
            >
                <Carousel v-bind="config">
                    <Slide v-for="slide in slides">
                        <div class="h-96 w-full">
                            <img
                                :src="slide.img"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </Slide>

                    <template #addons>
                        <Navigation />
                        <Pagination />
                    </template>
                </Carousel>
            </div>

            <!-- DETALLES -->
            <div class="flex flex-col justify-between">
                <div class="space-y-6">
                    <!-- NOMBRE -->
                    <div>
                        <p class="text-texto-secundario text-sm">
                            Segunda mano · Estado medio
                        </p>

                        <h1
                            class="text-rojo-fuerte mt-1 text-3xl font-semibold"
                        >
                            {{ article.name }}
                        </h1>
                    </div>

                    <!-- PRECIO -->
                    <div class="flex items-center gap-3">
                        <span class="text-azul text-3xl font-bold">
                            {{ article.price }} €
                        </span>

                        <span
                            v-if="article.onSale"
                            class="text-texto-secundario line-through"
                        >
                            {{ article.oldPrice }} €
                        </span>

                        <span
                            v-if="article.onSale"
                            class="bg-amarillo/30 text-naranja rounded-full px-3 py-1 text-sm font-semibold"
                        >
                            Oferta
                        </span>
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div>
                        <h2 class="text-rojo-fuerte mb-2 text-lg font-medium">
                            Descripción
                        </h2>

                        <p class="text-texto-secundario leading-relaxed">
                            {{ article.description }}
                        </p>
                    </div>

                    <!-- RATING -->
                    <div
                        class="bg-card border-borde rounded-xl border p-4 shadow-sm"
                    >
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">
                                <template v-for="star in 5" :key="star">
                                    <!-- COMPLETA -->
                                    <i
                                        v-if="
                                            star <=
                                            Math.floor(article.product_avg ?? 0)
                                        "
                                        class="pi pi-star-fill text-xl text-yellow-400"
                                    />

                                    <!-- MEDIA -->
                                    <i
                                        v-else-if="
                                            star ===
                                                Math.ceil(
                                                    article.product_avg ?? 0,
                                                ) &&
                                            (article.product_avg ?? 0) % 1 >=
                                                0.5
                                        "
                                        class="pi pi-star-half-fill text-xl text-yellow-400"
                                    />

                                    <!-- VACÍA -->
                                    <i
                                        v-else
                                        class="pi pi-star text-xl text-gray-300"
                                    />
                                </template>
                            </div>

                            <span class="font-semibold">
                                {{ (article.product_avg ?? 0).toFixed(1) }}
                            </span>
                        </div>

                        <p class="text-texto-secundario mt-2 text-sm">
                            {{ article.product_count ?? 0 }} valoraciones
                        </p>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="mt-8 flex gap-4">
                    <Button
                        class="w-full rounded-xl py-3 font-semibold text-white transition"
                        :class="
                            hasStock
                                ? 'bg-azul! hover:bg-azul/90!'
                                : 'cursor-not-allowed! bg-gray-300!'
                        "
                        :disabled="!hasStock"
                        @click="hasStock && cart.add(article)"
                    >
                        {{ hasStock ? "Añadir al carrito" : "Sin stock" }}
                    </Button>
                    <Button
                        class="bg-naranja! hover:bg-naranja/90! w-full rounded-xl py-3 font-semibold text-white transition"
                        @click="goToQuery"
                    >
                        Consultar producto
                    </Button>
                </div>
            </div>
        </div>
    </main>
</template>

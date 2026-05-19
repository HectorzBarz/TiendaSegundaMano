<script setup lang="ts">
import img1 from "@/assets/example/juguetes.webp";
import img2 from "@/assets/example/informatica.png";
import img3 from "@/assets/example/dados.png";
import img4 from "@/assets/example/nevera.svg";
import img5 from "@/assets/example/mesa.svg";
import img6 from "@/assets/example/patinete.svg";
import img7 from "@/assets/example/camiseta.svg";
import img8 from "@/assets/example/videojuegos.svg";
import img9 from "@/assets/example/vhs.svg";
import img10 from "@/assets/example/reprodutor_musical.svg";

import { Category } from "@/types";
import CategoryItemCard from "@/components/CategoryItemCard.vue";
import { useRouter } from "vue-router";
import { useArticleFiltersStore } from "@/stores/articleFilters";

/**
 * TODO
 * Articles will come filtered from the backend
 */
const categories = <Category[]>[
    {
        id: 1,
        img: img1,
        name: "Juguetes",
    },
    {
        id: 2,
        img: img2,
        name: "Informática",
    },
    {
        id: 3,
        img: img3,
        name: "Juegos de mesa",
    },
    {
        id: 4,
        img: img4,
        name: "Electrodomesticos",
    },
    {
        id: 5,
        img: img5,
        name: "Muebles",
    },
    {
        id: 6,
        img: img6,
        name: "Patinetes",
    },
    {
        id: 7,
        img: img7,
        name: "Ropa",
    },
    {
        id: 8,
        img: img8,
        name: "Videojuegos",
    },
    {
        id: 9,
        img: img9,
        name: "Películas",
    },
    {
        id: 10,
        img: img10,
        name: "Reproductores de música",
    },
];

const store = useArticleFiltersStore();
const router = useRouter();

function selectCategory(id: number) {
    store.setCategory(id);
    router.push("/articles");
}
</script>

<template>
    <div class="bg-fondo flex h-full flex-col gap-5">
        <!-- Title -->
        <div class="my-5 text-center align-middle">
            <h1 class="text-rojo-fuerte text-5xl font-bold">Categorías</h1>

            <p class="text-texto-secundario mt-2">
                Explora todas las categorías disponibles
            </p>
        </div>
        <!-- END Title -->

        <!-- Category Selector -->
        <div
            class="grid h-full w-full gap-2 overflow-hidden px-2 pb-5 text-center align-middle sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5"
        >
            <div v-for="category in categories" :key="category.id" class="">
                <RouterLink to="/articles" @click="selectCategory(category.id)">
                    <CategoryItemCard
                        :category="category"
                        class="bg-card border-borde text-texto"
                    />
                </RouterLink>
            </div>
        </div>
        <!-- END Category Selector -->
    </div>
</template>

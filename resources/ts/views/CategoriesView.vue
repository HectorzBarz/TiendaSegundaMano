<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";

import hampter from "/storage/app/public/img/hampter.jpg";

import { Category } from "@/types";
import CategoryItemCard from "@/components/CategoryItemCard.vue";

/**
 * TODO
 * Articles will come filtered from the backend
 */
const categories = <Category[]>[
    {
        id: 1,
        img: hampter,
        name: "Juguetes",
    },
    {
        id: 2,
        img: hampter,
        name: "Informática",
    },
    {
        id: 3,
        img: hampter,
        name: "Juegos de mesa",
    },
    {
        id: 4,
        img: hampter,
        name: "Electrodomesticos",
    },
    {
        id: 5,
        img: hampter,
        name: "Muebles",
    },
    {
        id: 6,
        img: hampter,
        name: "Patinetes",
    },
    {
        id: 7,
        img: hampter,
        name: "Ropa",
    },
    {
        id: 8,
        img: hampter,
        name: "Videojuegos",
    },
    {
        id: 9,
        img: hampter,
        name: "Películas",
    },
    {
        id: 10,
        img: hampter,
        name: "Reproductores de música",
    },
];
</script>

<template>
    <div class="flex h-full flex-col gap-5">
        <!-- Title -->
        <div class="my-5 text-center align-middle">
            <h1 class="text-5xl">Categorías</h1>
        </div>
        <!-- END Title -->

        <!-- Category Selector -->
        <div
            class="mb-5 grid h-full w-full grid-cols-5 overflow-hidden text-center align-middle"
        >
            <div v-for="category in categories" :key="category.id" class="">
                <RouterLink :to="'/articles/' + category.id">
                    <CategoryItemCard :category="category" />
                </RouterLink>
            </div>
        </div>
        <!-- END Category Selector -->
    </div>
</template>

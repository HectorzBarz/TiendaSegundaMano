<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";

import hampter from "/storage/app/public/img/hampter.jpg";

import { Category } from "@/types";

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
        <div class="mb-5 grid h-full grid-cols-5 text-center align-middle">
            <div
                v-for="categoria in categories"
                :key="categoria.id"
                class="group relative flex min-h-60 w-full items-center justify-center overflow-hidden border-y border-r border-gray-200 hover:cursor-pointer"
            >
                <RouterLink :to="'/articles/' + categoria.id">
                    <!-- Imagen -->
                    <img
                        :src="categoria.img"
                        alt="img"
                        class="absolute inset-0 h-full w-full object-cover opacity-40 transition-all group-hover:scale-115"
                    />

                    <!-- Texto -->
                    <span
                        class="relative z-10 text-xl font-semibold transition-all group-hover:text-2xl"
                    >
                        {{ categoria.name }}
                    </span>
                </RouterLink>
            </div>
        </div>
        <!-- END Category Selector -->
    </div>
</template>

<script setup lang="ts">
import { RouterLink } from "vue-router";
import hampter from "/storage/app/public/img/hampter.jpg";

const wishlist = [
    {
        id: 1,
        name: "Hámster adorable",
        price: 0.1,
        img: hampter,
    },
    {
        id: 2,
        name: "Auriculares gaming",
        price: 15,
        img: hampter,
    },
    {
        id: 3,
        name: "Silla gaming",
        price: 120,
        img: hampter,
    },
];
</script>

<template>
    <main class="bg-fondo min-h-screen px-6 py-10">
        <!-- TITLE -->
        <h1 class="text-rojo-fuerte mb-8 text-center text-4xl font-bold">
            Lista de deseados
        </h1>

        <!-- EMPTY -->
        <div
            v-if="!wishlist.length"
            class="text-texto-secundario text-center text-lg"
        >
            No tienes productos en tu lista
        </div>

        <!-- GRID -->
        <section
            class="mx-auto grid max-w-6xl grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3"
        >
            <div
                v-for="item in wishlist"
                :key="item.id"
                class="bg-card border-borde group overflow-hidden rounded-2xl border shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
            >
                <!-- LINK SOLO A LA PARTE NAVEGABLE -->
                <RouterLink :to="`/articles/${item.id}`" class="block">
                    <!-- IMAGE -->
                    <img :src="item.img" class="h-40 w-full object-cover" />

                    <!-- INFO -->
                    <div class="p-4">
                        <h2
                            class="text-rojo-fuerte group-hover:text-azul font-semibold transition"
                        >
                            {{ item.name }}
                        </h2>

                        <p class="text-texto-secundario mt-1">
                            {{ item.price }} €
                        </p>
                    </div>
                </RouterLink>

                <!-- BOTÓN FUERA DEL LINK -->
                <div class="px-4 pb-4">
                    <button
                        class="bg-azul hover:bg-azul/90 mt-2 w-full rounded-xl py-2 text-sm font-semibold text-white transition hover:cursor-pointer"
                    >
                        Añadir al carrito
                    </button>
                </div>
            </div>
        </section>
    </main>
</template>

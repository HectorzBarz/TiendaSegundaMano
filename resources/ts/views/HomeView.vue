<script setup lang="ts">
import hampter from "/storage/app/public/img/hampter.jpg";

const categorias = [
    { id: 1, img: hampter, name: "Muebles" },
    { id: 2, img: hampter, name: "Consolas" },
    { id: 3, img: hampter, name: "Cubertería" },
    { id: 4, img: hampter, name: "Deportes" },
    { id: 5, img: hampter, name: "Informática" },
    { id: 6, img: hampter, name: "Juegos de mesa" },
];
</script>

<template>
    <div class="flex h-full flex-col gap-5">
        <!-- Slider -->
        <div class="h-96 bg-red-300 text-center align-middle">
            <p>Hola caracola, esto será un slider</p>
        </div>
        <!-- END Slider -->

        <!-- Category Selector -->
        <div class="mb-5 grid h-full grid-cols-3 text-center align-middle">
            <div
                v-for="categoria in categorias"
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

<script setup lang="ts">
import hampter from "@/img/hampter.jpg";

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
    <div class="h-full flex flex-col gap-5">
        <!-- Slider -->
        <div class="bg-red-300 h-96 text-center align-middle">
            <p>Hola caracola, esto será un slider</p>
        </div>
        <!-- END Slider -->

        <!-- Category Selector -->
        <div class="grid grid-cols-3 text-center align-middle h-full mb-5">
            <div
                v-for="categoria in categorias"
                :key="categoria.id"
                class="group relative flex justify-center items-center min-h-60 border-r border-y border-gray-200 w-full overflow-hidden hover:cursor-pointer"
            >
                <RouterLink :to="'/category/' + categoria.id">
                    <!-- Imagen -->
                    <img
                        :src="categoria.img"
                        alt="img"
                        class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:scale-115 transition-all"
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

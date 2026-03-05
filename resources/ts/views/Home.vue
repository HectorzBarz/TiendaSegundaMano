<script setup lang="ts">
import hampter from "@/img/hampter.jpg";

const categorias = [
    { img: hampter, name: "Muebles" },
    { img: hampter, name: "Consolas" },
    { img: hampter, name: "Cubertería" },
    { img: hampter, name: "Deportes" },
    { img: hampter, name: "Informática" },
    { img: hampter, name: "Juegos de mesa" },
];
</script>

<template>
    <div class="h-full flex flex-col gap-5">
        <!-- Slider -->
        <div class="bg-red-300 h-96 text-center align-middle">
            <p>Hola caracola</p>
        </div>
        <!-- END Slider -->

        <!-- Category Selector -->
        <div class="grid grid-cols-3 text-center align-middle h-full mb-5">
            <div
                v-for="(categoria, index) in categorias"
                :key="index"
                class="group relative flex justify-center items-center min-h-60 border-r border-y border-gray-200 w-full overflow-hidden hover:cursor-pointer"
            >
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
            </div>
        </div>
        <!-- END Category Selector -->
    </div>
</template>

<script setup lang="ts">
import hampter from "@/img/hampter.jpg";

const articulos = [
    { id: 1, img: hampter, name: "Artículo " },
    { id: 2, img: hampter, name: "Artículo " },
    { id: 3, img: hampter, name: "Artículo " },
    { id: 4, img: hampter, name: "Artículo " },
    { id: 5, img: hampter, name: "Artículo " },
    { id: 6, img: hampter, name: "Artículo " },
    { id: 7, img: hampter, name: "Artículo " },
    { id: 8, img: hampter, name: "Artículo " },
    { id: 9, img: hampter, name: "Artículo " },
    { id: 10, img: hampter, name: "Artículo " },
];
</script>

<template>
    <div class="h-full flex flex-col gap-5">
        <!-- Slider -->
        <div class="text-center my-5 align-middle">
            <h1 class="text-5xl">Hola caracola, esto es una Categoría</h1>
        </div>
        <!-- END Slider -->

        <!-- Category Selector -->
        <div
            class="grid grid-cols-5 gap-3 text-center align-middle h-full mb-5"
        >
            <div
                v-for="articulo in articulos"
                :key="articulo.id"
                class="shadow-xl flex rounded-t-xl justify-center items-center min-h-60 -200 w-full overflow-hidden hover:cursor-pointer"
            >
                <div>
                    <!-- Imagen -->
                    <img :src="articulo.img" alt="img" class="w-full h-full" />

                    <!-- Texto -->
                    <h2
                        class="text-xl font-semibold transition-all group-hover:text-2xl"
                    >
                        {{ articulo.name }}
                    </h2>
                </div>
            </div>
        </div>
        <!-- END Category Selector -->
    </div>
</template>

<script setup lang="ts">
import Button from "@volt/Button.vue";
import hampter from "/storage/app/public/img/hampter.jpg";

import { useCopyToClipboard } from "@/composables/useCopyToClipboard";

import { Article } from "@/types";

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
};

const emailToCopy = "hectorrodriguezbar99@gmail.com";

const { copyToClipboard } = useCopyToClipboard(emailToCopy);
</script>

<template>
    <main class="mx-auto max-w-6xl px-6 py-10">
        <div class="grid gap-10 md:grid-cols-2">
            <!-- Imagen -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
                <img
                    :src="article.img"
                    alt="img"
                    class="h-100 w-full object-cover"
                />
            </div>

            <!-- Detalles -->
            <div class="flex flex-col justify-between">
                <div class="space-y-6">
                    <!-- Nombre + categoría -->
                    <div>
                        <p class="text-sm text-gray-500">
                            Segunda mano · Como nuevo
                        </p>
                        <h1 class="mt-1 text-3xl font-semibold">
                            {{ article.name }}
                        </h1>
                    </div>

                    <!-- Precio -->
                    <div class="flex items-center gap-3">
                        <span class="text-3xl font-bold text-green-600">
                            {{ article.price }} €
                        </span>

                        <span
                            v-if="article.onSale"
                            class="text-gray-400 line-through"
                        >
                            {{ article.oldPrice }} €
                        </span>

                        <span
                            v-if="article.onSale"
                            class="rounded-full bg-green-100 px-2 py-1 text-sm text-green-700"
                        >
                            Oferta
                        </span>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <h2 class="mb-2 text-lg font-medium">Descripción</h2>
                        <p class="leading-relaxed text-gray-600">
                            {{ article.description }}
                        </p>
                    </div>

                    <!-- Info vendedor -->
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-sm text-gray-500">Vendido por</p>
                        <p class="font-medium">Héctor Rodríguez</p>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-8 flex gap-4">
                    <Button
                        class="w-full rounded-xl bg-black py-3 text-white hover:bg-gray-800"
                    >
                        Añadir al carrito
                    </Button>

                    <Button
                        class="w-full rounded-xl bg-gray-100 py-3 hover:bg-gray-200"
                        @click="copyToClipboard"
                    >
                        Contactar
                    </Button>
                </div>
            </div>
        </div>
    </main>
</template>

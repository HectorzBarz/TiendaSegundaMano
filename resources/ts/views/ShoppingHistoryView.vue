<script setup lang="ts">
import hampter from "/storage/app/public/img/hampter.jpg";

const orders = [
    {
        id: 1,
        name: "Hámster adorable",
        price: 0.1,
        date: "12/01/2026",
        delivered: false,
        img: hampter,
    },
    {
        id: 2,
        name: "Teclado mecánico",
        price: 25,
        date: "03/01/2026",
        delivered: true,
        img: hampter,
    },
    {
        id: 3,
        name: "Monitor 24”",
        price: 90,
        date: "28/12/2025",
        delivered: true,
        img: hampter,
    },
];
</script>

<template>
    <main class="bg-fondo min-h-screen px-6 py-10">
        <!-- TITLE -->
        <h1 class="text-rojo-fuerte mb-8 text-center text-4xl font-bold">
            Historial de compras
        </h1>

        <!-- EMPTY -->
        <div
            v-if="!orders.length"
            class="text-texto-secundario text-center text-lg"
        >
            No tienes compras todavía
        </div>

        <!-- LIST -->
        <section class="mx-auto grid max-w-5xl gap-5">
            <div
                v-for="order in orders"
                :key="order.id"
                class="bg-card border-borde flex items-center gap-4 rounded-2xl border p-4 shadow-sm"
            >
                <!-- IMAGE -->
                <img
                    :src="order.img"
                    class="h-16 w-16 rounded-xl object-cover"
                />

                <!-- INFO -->
                <div class="flex-1">
                    <h2 class="text-rojo-fuerte font-semibold">
                        {{ order.name }}
                    </h2>

                    <p class="text-texto-secundario text-sm">
                        {{ order.date }}
                    </p>
                </div>

                <!-- PRICE -->
                <div class="text-azul font-bold">{{ order.price }} €</div>

                <!-- STATUS -->
                <span
                    class="rounded-full px-3 py-1 text-xs font-semibold"
                    :class="{
                        'bg-amarillo/30 text-naranja':
                            order.delivered === false,
                        'bg-azul/10 text-azul': order.delivered === true,
                    }"
                >
                    {{ order.delivered ? "Entregado" : "Enviado" }}
                </span>
            </div>
        </section>
    </main>
</template>

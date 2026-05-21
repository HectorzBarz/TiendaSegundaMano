<script setup lang="ts">
import { RouterLink, useRouter } from "vue-router";
const hampter = "/images/placeholder.jpg";
import { useCartStore } from "@/stores/cart";
import { useAuthStore } from "@/stores/auth";

const auth = useAuthStore();
const router = useRouter();

// Redirección si el usuario no está autenticado
if (!auth.isAuthenticated) {
    router.push("login");
}

const wishlist = [
    {
        id: 1,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 1",
        onSale: true,
        categoryId: 1,
        product_avg: 3.2,
        product_count: 3,
        stock: 0,
    },
    {
        id: 2,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 2 ",
        onSale: false,
        categoryId: 2,
        product_avg: 4.1,
        product_count: 3,
        stock: 1,
    },
    {
        id: 3,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 3",
        onSale: false,
        categoryId: 1,
        product_avg: 3.5,
        product_count: 3,
        stock: 1,
    },
];

const cart = useCartStore();

const hasStock = (item: any) => (item.stock ?? 0) > 0;
</script>

<template>
    <main class="bg-fondo min-h-screen px-6 py-10">
        <h1 class="text-rojo-fuerte mb-8 text-center text-4xl font-bold">
            Lista de deseados
        </h1>

        <div
            v-if="!wishlist.length"
            class="text-texto-secundario text-center text-lg"
        >
            No tienes productos en tu lista
        </div>

        <section
            class="mx-auto grid max-w-6xl grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3"
        >
            <div
                v-for="item in wishlist"
                :key="item.id"
                class="bg-card border-borde group relative overflow-hidden rounded-2xl border shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                :class="!hasStock(item) ? 'pointer-events-none opacity-50' : ''"
            >
                <!-- 🔴 BADGE STOCK -->
                <span
                    class="absolute top-3 left-3 z-10 rounded-full px-3 py-1 text-xs font-semibold"
                    :class="
                        hasStock(item)
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-600'
                    "
                >
                    {{ hasStock(item) ? "En stock" : "Sin stock" }}
                </span>

                <!-- 🚫 OVERLAY SIN STOCK -->
                <div
                    v-if="!hasStock(item)"
                    class="absolute inset-0 z-10 flex items-center justify-center bg-black/40"
                >
                    <span
                        class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow"
                    >
                        Producto agotado
                    </span>
                </div>

                <!-- LINK -->
                <RouterLink
                    :to="`/articles/${item.id}`"
                    class="block"
                    :class="!hasStock(item) ? 'pointer-events-none' : ''"
                >
                    <img
                        :src="item.img"
                        class="h-40 w-full object-cover transition"
                        :class="!hasStock(item) ? 'grayscale' : ''"
                    />

                    <div class="p-4">
                        <h2
                            class="text-rojo-fuerte font-semibold transition"
                            :class="
                                hasStock(item) ? 'group-hover:text-azul' : ''
                            "
                        >
                            {{ item.name }}
                        </h2>

                        <p class="text-texto-secundario mt-1">
                            {{ item.price }} €
                        </p>
                    </div>
                </RouterLink>

                <!-- BUTTON -->
                <div class="px-4 pb-4">
                    <button
                        class="mt-2 w-full rounded-xl py-2 text-sm font-semibold transition hover:cursor-pointer"
                        :class="
                            hasStock(item)
                                ? 'bg-azul hover:bg-azul/90 text-white'
                                : 'cursor-not-allowed bg-gray-300 text-gray-500'
                        "
                        :disabled="!hasStock(item)"
                        @click="hasStock(item) && cart.add(item)"
                    >
                        {{ hasStock(item) ? "Añadir al carrito" : "Sin stock" }}
                    </button>
                </div>
            </div>
        </section>
    </main>
</template>

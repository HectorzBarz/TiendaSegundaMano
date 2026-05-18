<script setup lang="ts">
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { ref } from "vue";
import { useCartStore } from "@/stores/cart";
import CartDrawer from "@/components/CartDrawer.vue";

const articlesStore = useArticleFiltersStore();

const cart = useCartStore();

function resetArticles() {
    articlesStore.reset();
}
</script>

<template>
    <header
        class="border-borde bg-card/95 sticky top-0 z-50 border-b shadow-sm backdrop-blur-md"
    >
        <!-- DESKTOP -->
        <section
            class="mx-auto hidden h-24 max-w-7xl items-center justify-between px-6 md:flex"
        >
            <!-- LOGO -->
            <RouterLink to="/">
                <div
                    class="hover:bg-amarillo/10 rounded-3xl p-2 transition-all duration-200"
                >
                    <img
                        src="/public/img/favicon.svg"
                        alt="logo"
                        class="h-14 w-fit"
                    />
                </div>
            </RouterLink>

            <!-- NAV -->
            <nav class="flex items-center gap-2">
                <RouterLink to="/">
                    <div
                        class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro rounded-2xl px-5 py-3 font-semibold transition-all duration-200"
                    >
                        Inicio
                    </div>
                </RouterLink>

                <RouterLink to="/articles" @click="resetArticles">
                    <div
                        class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro rounded-2xl px-5 py-3 font-semibold transition-all duration-200"
                    >
                        Productos
                    </div>
                </RouterLink>

                <RouterLink to="/categories">
                    <div
                        class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro rounded-2xl px-5 py-3 font-semibold transition-all duration-200"
                    >
                        Categorías
                    </div>
                </RouterLink>

                <RouterLink to="/account">
                    <div
                        class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro rounded-2xl px-5 py-3 font-semibold transition-all duration-200"
                    >
                        Área personal
                    </div>
                </RouterLink>
            </nav>

            <!-- ACTIONS -->
            <div class="flex items-center gap-3">
                <!-- CART -->
                <button
                    @click="cart.openCart()"
                    class="bg-azul/10 text-azul hover:bg-azul relative flex h-12 w-12 items-center justify-center rounded-2xl transition hover:text-white"
                >
                    <i class="pi pi-shopping-cart text-xl"></i>

                    <span
                        v-if="cart.count"
                        class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white"
                    >
                        {{ cart.count }}
                    </span>
                </button>
            </div>
        </section>

        <!-- MOBILE -->
        <section class="flex h-20 items-center justify-around px-4 md:hidden">
            <RouterLink to="/">
                <div
                    class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200"
                >
                    <i class="pi pi-home text-xl"></i>
                </div>
            </RouterLink>

            <RouterLink to="/articles" @click="resetArticles">
                <div
                    class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200"
                >
                    <i class="pi pi-shop text-xl"></i>
                </div>
            </RouterLink>

            <RouterLink to="/categories">
                <div
                    class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200"
                >
                    <i class="pi pi-book text-xl"></i>
                </div>
            </RouterLink>

            <RouterLink to="/account">
                <div
                    class="text-rojo-fuerte hover:bg-amarillo/20 hover:text-rojo-claro flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200"
                >
                    <i class="pi pi-user text-xl"></i>
                </div>
            </RouterLink>

            <button
                @click="cart.openCart()"
                class="bg-azul/10 text-azul hover:bg-azul relative flex h-12 w-12 items-center justify-center rounded-2xl transition hover:text-white"
            >
                <i class="pi pi-shopping-cart text-xl"></i>

                <span
                    v-if="cart.count"
                    class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white"
                >
                    {{ cart.count }}
                </span>
            </button>
        </section>
    </header>
</template>

<script setup lang="ts">
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { ref } from "vue";
import { useCartStore } from "@/stores/cart";
import CartDrawer from "@/components/CartDrawer.vue";
import { useAuthStore } from "@/stores/auth"; // Añadido
import { useRouter } from "vue-router"; // Añadido

const articlesStore = useArticleFiltersStore();
const cart = useCartStore();

// Instancias añadidas para el Logout
const auth = useAuthStore();
const router = useRouter();

function resetArticles() {
    articlesStore.reset();
}

// Función añadida
async function handleLogout() {
    await auth.logout();
    router.push("/login");
}
</script>

<template>
    <header
        class="border-borde bg-card/95 sticky top-0 z-50 border-b shadow-sm backdrop-blur-md"
    >
        <section
            class="mx-auto hidden h-24 max-w-7xl items-center justify-between px-6 md:flex"
        >
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

            <div class="flex items-center gap-3">
                <button
                    v-if="auth.isAuthenticated"
                    @click="handleLogout"
                    title="Cerrar sesión"
                    class="text-rojo-fuerte relative flex h-12 w-12 items-center justify-center rounded-2xl transition hover:bg-red-100"
                >
                    <i class="pi pi-sign-out text-xl"></i>
                </button>

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
                v-if="auth.isAuthenticated"
                @click="handleLogout"
                class="text-rojo-fuerte flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200 hover:bg-red-100"
            >
                <i class="pi pi-sign-out text-xl"></i>
            </button>

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

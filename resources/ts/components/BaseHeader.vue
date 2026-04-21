<script setup lang="ts">
import { useArticleFiltersStore } from "@/stores/articleFilters";

const store = useArticleFiltersStore();

function resetArticles() {
    store.reset();
}
</script>

<template>
    <div>
        <section
            class="hidden h-20 content-center items-center justify-between bg-linear-to-t from-gray-100 to-gray-200 md:flex"
        >
            <RouterLink to="/">
                <div class="px-10">
                    <img
                        src="/public/img/favicon.svg"
                        alt="logo"
                        class="h-16 w-fit"
                    /></div
            ></RouterLink>
            <nav class="flex justify-around">
                <RouterLink to="/">
                    <ul
                        class="px-5 py-2 transition-all hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                    >
                        Inicio
                    </ul>
                </RouterLink>
                <RouterLink to="/articles" @click="resetArticles">
                    <ul
                        class="px-5 py-2 transition-all hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                    >
                        Productos
                    </ul>
                </RouterLink>

                <RouterLink to="/categories">
                    <ul
                        class="px-5 py-2 transition-all hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                    >
                        Categorias
                    </ul>
                </RouterLink>
                <ul
                    class="px-5 py-2 transition-all hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                >
                    Área personal
                </ul>
            </nav>
            <!-- Shopping cart -->
            <div class="p-5">
                <i
                    style="font-size: 1.5rem"
                    class="pi-shopping-cart pi cursor-pointer hover:text-white hover:transition-all hover:duration-300"
                />
            </div>
        </section>

        <section
            class="flex h-20 content-center items-center justify-between bg-linear-to-t from-gray-100 to-gray-200 md:hidden"
        >
            <nav class="flex w-full justify-around gap-2">
                <RouterLink to="/">
                    <i
                        style="font-size: 1.5rem"
                        class="pi-home pi cursor-pointer hover:text-white hover:transition-all hover:duration-300"
                    />
                </RouterLink>
                <RouterLink to="/articles" @click="resetArticles">
                    <i
                        style="font-size: 1.5rem"
                        class="pi-shop pi cursor-pointer hover:text-white hover:transition-all hover:duration-300"
                    />
                </RouterLink>

                <RouterLink to="/categories">
                    <i
                        style="font-size: 1.5rem"
                        class="pi-book pi cursor-pointer hover:text-white hover:transition-all hover:duration-300"
                    />
                </RouterLink>
                <RouterLink to="#">
                    <i
                        style="font-size: 1.5rem"
                        class="pi-user pi cursor-pointer hover:text-white hover:transition-all hover:duration-300"
                    />
                </RouterLink>
                <!-- Shopping cart -->
                <div>
                    <i
                        style="font-size: 1.5rem"
                        class="pi-shopping-cart pi cursor-pointer hover:text-white hover:transition-all hover:duration-300"
                    />
                </div>
            </nav>
        </section>
    </div>
</template>

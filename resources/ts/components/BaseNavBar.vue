<script setup lang="ts">
import { useArticleFiltersStore } from "@/stores/articleFilters";

const store = useArticleFiltersStore();

const props = defineProps<{
    option: number;
}>();

function resetArticles() {
    store.reset();
}
</script>

<template>
    <section v-if="option === 0">
        <div
            class="flex h-20 content-center items-center justify-between bg-linear-to-t from-gray-100 to-gray-200"
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
                        class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                    >
                        Inicio
                    </ul>
                </RouterLink>
                <RouterLink to="/articles" @click="resetArticles">
                    <ul
                        class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                    >
                        Productos
                    </ul>
                </RouterLink>

                <RouterLink to="/categories">
                    <ul
                        class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                    >
                        Categorias
                    </ul>
                </RouterLink>
                <ul
                    class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                >
                    Área personal
                </ul>
            </nav>
        </div>
    </section>
    <section v-if="option === 1">aaa</section>
    <!-- Shopping cart -->
    <div class="p-5">
        <i
            style="font-size: 1.5rem"
            class="pi-shopping-cart pi cursor-pointer hover:scale-110 hover:text-white hover:transition-all hover:duration-300"
        />
    </div>
</template>

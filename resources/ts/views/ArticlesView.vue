<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";

import hampter from "/storage/app/public/img/hampter.jpg";

import { Article } from "@/types";
import { computed } from "vue";
import ArticleItemCard from "@/components/ArticleItemCard.vue";

const props = defineProps<{
    categoryId: number;
}>();

/**
 * TODO
 * Articles will come filtered from the backend
 */
const articles = <Article[]>[
    {
        id: 1,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 1",
        onSale: true,
        categoryId: 1,
    },
    {
        id: 2,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 2 ",
        onSale: false,
        categoryId: 2,
    },
    {
        id: 3,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 3",
        onSale: false,
        categoryId: 1,
    },
    {
        id: 4,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 4",
        onSale: true,
        categoryId: 3,
    },
    {
        id: 5,
        img: hampter,
        price: 10,
        name: "Artículo 5",
        onSale: false,
        categoryId: 1,
    },
    {
        id: 6,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 6",
        onSale: true,
        categoryId: 4,
    },
    {
        id: 7,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 7",
        onSale: false,
        categoryId: 6,
    },
    {
        id: 8,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 8",
        onSale: false,
        categoryId: 5,
    },
    {
        id: 9,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 9",
        onSale: false,
        categoryId: 6,
    },
    {
        id: 10,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 10",
        onSale: false,
        categoryId: 2,
    },
];

const filteredArticles = computed(() => {
    const categoryId = props.categoryId;

    if (categoryId === 0) return articles;

    return articles.filter(
        (article) => article.categoryId === props.categoryId,
    );
});
</script>

<template>
    <div class="flex h-full flex-col gap-5">
        <!-- Title -->
        <div class="my-5 text-center align-middle">
            <h1 class="text-5xl">Artículos</h1>
        </div>
        <!-- END Title -->

        <section class="flex flex-col lg:flex-row">
            <!-- Article filters -->

            <FilterBase
                :suggestions="articles"
                class="lg:w-1/3 xl:max-w-1/5 xl:min-w-1/5"
            />

            <!-- END Article filters -->

            <!-- Article List -->
            <div
                v-if="filteredArticles.length"
                class="grid h-full w-full gap-3 text-center align-middle sm:grid-cols-2 md:grid-cols-3 lg:px-5 xl:grid-cols-5"
            >
                <ArticleItemCard
                    v-for="article in filteredArticles"
                    :key="article.id"
                    :article="article"
                />
            </div>
            <div
                class="m-auto flex h-full w-full justify-center p-5 text-center text-3xl text-red-500"
                v-else
            >
                No se han encontrado artículos de esta categoría
            </div>
            <!-- END Article List -->
        </section>
    </div>
</template>

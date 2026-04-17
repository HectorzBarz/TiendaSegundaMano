<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";
import ArticleItemCard from "@/components/ArticleItemCard.vue";
import hampter from "/storage/app/public/img/hampter.jpg";

import { computed } from "vue";
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { Article } from "@/types";

const store = useArticleFiltersStore();

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
    return articles.filter((article) => {
        const matchesCategory =
            !store.categoryId || article.categoryId === store.categoryId;

        const matchesName =
            !store.articleName ||
            article.name
                .toLowerCase()
                .includes(store.articleName.toLowerCase());

        const matchesPrice =
            article.price >= store.minPrice && article.price <= store.maxPrice;

        const matchesSale = !store.onSale || article.onSale;

        return matchesCategory && matchesName && matchesPrice && matchesSale;
    });
});
</script>

<template>
    <div class="flex h-full flex-col gap-5">
        <div class="my-5 text-center">
            <h1 class="text-5xl">Artículos</h1>
        </div>

        <section class="flex flex-col lg:flex-row">
            <FilterBase
                :suggestions="articles"
                class="lg:w-1/3 xl:max-w-1/5 xl:min-w-1/5"
            />

            <div
                v-if="filteredArticles.length"
                class="grid w-full grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5"
            >
                <ArticleItemCard
                    v-for="article in filteredArticles"
                    :key="article.id"
                    :article="article"
                />
            </div>

            <div v-else class="m-auto flex h-full w-full justify-center">
                <span class="text-3xl text-red-500">
                    No se han encontrado artículos</span
                >
            </div>
        </section>
    </div>
</template>

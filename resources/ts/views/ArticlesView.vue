<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";
import ArticleItemCard from "@/components/ArticleItemCard.vue";

import { computed, onMounted, ref, watch } from "vue";
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { Article, ApiResponse, RawArticle } from "@/types";
import { useRoute } from "vue-router";
import axios from "axios";

const store = useArticleFiltersStore();
const route = useRoute();

const articles = ref<Article[]>([]);
const loading = ref(false);

/**
 * FETCH ARTICLES FROM BACKEND
 */
const fetchArticles = async () => {
    try {
        loading.value = true;

        const response = await axios.get<
            RawArticle[] | ApiResponse<RawArticle[]>
        >("api/articles");

        let raw: RawArticle[] = [];

        if (Array.isArray(response.data)) {
            raw = response.data;
        } else if (response.data?.data) {
            raw = response.data.data;
        }

        articles.value = raw.map((a): Article => {
            let img = "";

            if (Array.isArray(a.images) && a.images.length > 0) {
                img = a.images[0];
            } else if (typeof a.images === "string") {
                try {
                    const parsed = JSON.parse(a.images);
                    img = parsed?.[0] ?? "";
                } catch {
                    img = a.images;
                }
            }

            const baseUrl = axios.defaults.baseURL
                ? axios.defaults.baseURL.replace(/\/api\/?$/, "")
                : "http://localhost:8000";

            return {
                ...a,
                onSale: Boolean(a.on_sale),
                oldPrice: a.old_price,
                img: img
                    ? img.startsWith("http")
                        ? img
                        : `${baseUrl}/storage/${img}`
                    : "https://placehold.co/600x400?text=Sin+Imagen",
            };
        });
    } catch (e) {
        console.error("Error cargando artículos:", e);
        articles.value = [];
    } finally {
        loading.value = false;
    }
};

/**
 * FILTERS
 */
const filteredArticles = computed(() => {
    return articles.value.filter((article) => {
        const matchesCategory =
            store.categoryId === 0 || article.categoryId === store.categoryId;

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

/**
 * SYNC CATEGORY FROM URL
 */
watch(
    () => route.query.category,
    (newVal) => {
        if (newVal) {
            store.setCategory(Number(newVal));
        } else {
            store.reset();
        }
    },
    { immediate: true },
);

onMounted(() => {
    fetchArticles();
});
</script>

<template>
    <div class="bg-fondo flex h-full flex-col gap-5">
        <!-- TITLE -->
        <div class="my-5 text-center">
            <h1 class="text-rojo-fuerte text-5xl font-bold">Artículos</h1>

            <p class="text-texto-secundario mt-2">
                Encuentra productos disponibles en la plataforma
            </p>
        </div>

        <section class="flex flex-col lg:flex-row lg:items-start">
            <FilterBase
                :suggestions="articles"
                class="bg-card border-borde rounded-2xl border lg:w-1/3 xl:max-w-1/5 xl:min-w-1/5"
            />

            <!-- LIST -->
            <div
                v-if="filteredArticles.length"
                class="grid w-full grid-cols-1 gap-3 p-5 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5"
            >
                <ArticleItemCard
                    v-for="article in filteredArticles"
                    :key="article.id"
                    :article="article"
                />
            </div>

            <!-- EMPTY STATE -->
            <div v-else class="m-auto flex h-full w-full justify-center">
                <span class="text-rojo-claro text-3xl font-semibold">
                    No se han encontrado artículos
                </span>
            </div>
        </section>
    </div>
</template>

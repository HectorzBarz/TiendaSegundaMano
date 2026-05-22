<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { api } from "@/stores/auth";
import ArticleReviews from "@/components/ArticleReviews.vue";

import Button from "@volt/Button.vue";
import { Carousel, Slide, Navigation, Pagination } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

import { useCartStore } from "@/stores/cart";
import type { Article } from "@/types";

const cart = useCartStore();
const router = useRouter();
const route = useRoute();

const article = ref<Article | null>(null);
const loading = ref(true);
const notFound = ref(false);

/**
 * FETCH ARTICLE
 */
const fetchArticle = async () => {
    try {
        loading.value = true;

        const id = route.params.id;

        const { data } = await api.get(`/articles/${id}`);

        let rawImages: string[] = [];

        if (Array.isArray(data.images)) {
            rawImages = data.images;
        } else if (typeof data.images === "string") {
            try {
                rawImages = JSON.parse(data.images);
            } catch {
                rawImages = [data.images];
            }
        }

        const baseUrl =
            api.defaults.baseURL?.replace(/\/api\/?$/, "") ||
            "http://localhost:8000";

        const images = rawImages.map((img: string) =>
            img.startsWith("http") ? img : `${baseUrl}/storage/${img}`,
        );

        article.value = {
            ...data,
            images,
            onSale: Boolean(data.on_sale),
            oldPrice: data.old_price,
        };

        notFound.value = false;
    } catch (err) {
        console.error("Error cargando artículo:", err);
        article.value = null;
        notFound.value = true;
    } finally {
        loading.value = false;
    }
};

onMounted(fetchArticle);

/**
 * COMPUTED
 */
const hasStock = computed(() => (article.value?.stock ?? 0) > 0);

const goToQuery = () => {
    if (!article.value) return;

    router.push({
        name: "article-query",
        params: { id: article.value.id },
    });
};

const handleReviewAdded = () => {
    fetchArticle();
};
</script>

<template>
    <!-- LOADING -->
    <div v-if="loading" class="flex h-screen items-center justify-center">
        <p class="text-xl">Cargando artículo...</p>
    </div>

    <!-- NOT FOUND -->
    <div
        v-else-if="notFound || !article"
        class="flex h-screen flex-col items-center justify-center gap-4"
    >
        <p class="text-2xl font-semibold text-red-600">
            Este artículo no existe
        </p>

        <Button
            label="Volver"
            icon="pi pi-arrow-left"
            @click="router.push('/')"
        />
    </div>

    <!-- CONTENT -->
    <main v-else class="bg-fondo mx-auto max-w-6xl p-10">
        <div class="grid gap-10 md:grid-cols-2">
            <!-- CAROUSEL -->
            <div
                class="bg-card border-borde overflow-hidden rounded-2xl border shadow-sm"
            >
                <Carousel :itemsToShow="1" :wrapAround="true">
                    <Slide v-for="(img, i) in article.images" :key="i">
                        <div
                            class="flex h-125 w-full items-center justify-center bg-gray-100"
                        >
                            <img
                                :src="img"
                                class="h-full w-full object-contain"
                            />
                        </div>
                    </Slide>

                    <template #addons>
                        <Navigation />
                        <Pagination />
                    </template>
                </Carousel>
            </div>

            <!-- DETAILS -->
            <div class="flex flex-col justify-between">
                <div class="space-y-6">
                    <div>
                        <p class="text-texto-secundario text-sm">
                            Segunda mano · Estado del producto
                        </p>

                        <h1
                            class="text-rojo-fuerte mt-1 text-3xl font-semibold"
                        >
                            {{ article.name }}
                        </h1>

                        <div
                            v-if="article.product_count! > 0"
                            class="mt-2 flex items-center gap-2"
                        >
                            <div class="flex gap-0.5 text-sm">
                                <i
                                    v-for="star in 5"
                                    :key="star"
                                    :class="
                                        star <= Math.round(article.product_avg!)
                                            ? 'pi pi-star-fill text-yellow-400'
                                            : 'pi pi-star text-gray-300'
                                    "
                                ></i>
                            </div>
                            <span class="text-texto text-sm font-bold">
                                {{ article.product_avg }}
                            </span>
                            <span class="text-texto-secundario text-xs">
                                ({{ article.product_count }} valoraciones)
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-azul text-3xl font-bold">
                            {{ article.price }} €
                        </span>

                        <span
                            v-if="article.onSale"
                            class="text-texto-secundario line-through"
                        >
                            {{ article.oldPrice }} €
                        </span>

                        <span
                            v-if="article.onSale"
                            class="bg-amarillo/30 text-naranja rounded-full px-3 py-1 text-sm font-semibold"
                        >
                            Oferta
                        </span>
                    </div>

                    <div
                        v-if="article.stock !== undefined"
                        class="flex items-center gap-2"
                    >
                        <span
                            class="text-sm font-medium"
                            :class="
                                hasStock
                                    ? 'text-green-600'
                                    : 'text-red-500'
                            "
                        >
                            {{
                                hasStock
                                    ? 'Stock: ' + article.stock + ' uds.'
                                    : 'Agotado'
                            }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-rojo-fuerte mb-2 text-lg font-medium">
                            Descripción
                        </h2>

                        <p class="text-texto-secundario leading-relaxed">
                            {{ article.description }}
                        </p>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="mt-8 flex gap-4">
                    <Button
                        class="w-full rounded-xl py-3 font-semibold text-white"
                        :class="
                            hasStock
                                ? 'bg-azul! hover:bg-azul/90!'
                                : 'cursor-not-allowed! bg-gray-300!'
                        "
                        :disabled="!hasStock"
                        @click="hasStock && cart.add(article)"
                    >
                        {{ hasStock ? "Añadir al carrito" : "Sin stock" }}
                    </Button>

                    <Button
                        class="bg-naranja! hover:bg-naranja/90! w-full rounded-xl py-3 font-semibold text-white"
                        @click="goToQuery"
                    >
                        Consultar producto
                    </Button>
                </div>
            </div>
        </div>

        <ArticleReviews
            :article-id="article.id"
            @review-added="handleReviewAdded"
        />
    </main>
</template>

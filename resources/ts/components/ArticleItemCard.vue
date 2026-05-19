<script setup lang="ts">
import { Article } from "@/types";
import BaseHoverCard from "./BaseHoverCard.vue";

import { useCartStore } from "@/stores/cart";

const props = defineProps<{
    article: Article;
}>();

const cart = useCartStore();

const hasStock = (props.article.stock ?? 0) > 0;
</script>
<template>
    <BaseHoverCard :to="'articles/' + article.id">
        <div
            class="flex flex-col items-center justify-center overflow-hidden rounded-t-lg hover:cursor-pointer"
        >
            <!-- Image -->
            <img :src="props.article.img" alt="img" class="h-full w-full" />

            <!-- Article name -->
            <h2
                class="text-xl font-semibold transition-transform duration-300 group-hover:scale-110"
            >
                {{ props.article.name }}
            </h2>

            <!-- Article price -->
            <div>
                <h3
                    class="flex justify-center gap-2 text-lg font-semibold transition-all"
                    :class="article.onSale ? 'text-red-600' : 'text-gray-600'"
                >
                    {{ props.article.price }}€
                    <span
                        v-if="props.article.onSale"
                        class="flex items-center text-sm text-gray-600 line-through"
                    >
                        Antiguamente: {{ props.article.oldPrice }}€
                    </span>
                </h3>
            </div>

            <!-- Rating section -->
            <div class="flex gap-2">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1">
                        <template v-for="star in 5" :key="star">
                            <!-- COMPLETA -->
                            <i
                                v-if="
                                    star <= Math.floor(article.product_avg ?? 0)
                                "
                                class="pi pi-star-fill text-xl text-yellow-400"
                            />

                            <!-- MEDIA -->
                            <i
                                v-else-if="
                                    star ===
                                        Math.ceil(article.product_avg ?? 0) &&
                                    (article.product_avg ?? 0) % 1 >= 0.5
                                "
                                class="pi pi-star-half-fill text-xl text-yellow-400"
                            />

                            <!-- VACÍA -->
                            <i
                                v-else
                                class="pi pi-star text-xl text-gray-300"
                            />
                        </template>
                    </div>

                    <span class="font-semibold">
                        {{ (article.product_avg ?? 0).toFixed(1) }}
                    </span>
                </div>
            </div>
            <!-- END Rating section -->

            <!-- Cart section -->
            <div>
                <i
                    class="pi pi-shopping-cart m-2 rounded-4xl p-2 transition"
                    :class="
                        hasStock
                            ? 'cursor-pointer bg-green-500 hover:bg-green-400'
                            : 'cursor-not-allowed bg-gray-300 text-gray-500'
                    "
                    @click.prevent="hasStock && cart.add(article)"
                >
                    <span v-if="hasStock"> Añadir al carrito</span>
                </i>

                <!-- Texto opcional UX -->
                <p v-if="!hasStock" class="mt-1 text-xs text-red-500">
                    Sin stock
                </p>
            </div>
            <!-- END Cart section -->
        </div>
    </BaseHoverCard>
</template>

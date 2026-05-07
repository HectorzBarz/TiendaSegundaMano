<script setup lang="ts">
import { ArticleCard } from "@/types";
import BaseHoverCard from "./BaseHoverCard.vue";

const props = defineProps<{
    article: ArticleCard;
}>();
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
            <section class="flex gap-2">
                <i class="pi pi-star text-yellow-400" />
                <i class="pi pi-star text-yellow-400" />
                <i class="pi pi-star text-yellow-400" />
                <i class="pi pi-star text-yellow-400" />
                <i class="pi pi-star text-yellow-400" />
            </section>
            <!-- END Rating section -->

            <!-- Cart section -->
            <div>
                <div>
                    <i
                        class="pi pi-shopping-cart m-2 rounded-4xl bg-green-500 p-2 hover:bg-green-400"
                        >+</i
                    >
                </div>

                <!-- 
                    TODO:
                    ! Based on the shopping cart options it will let you remove the article 
                
                <div>
                    <i
                        class="pi pi-shopping-cart rounded-4xl bg-red-500 p-2 hover:bg-red-400"
                        >-</i
                    >
                </div>
                -->
            </div>
            <!-- END Cart section -->
        </div>
    </BaseHoverCard>
</template>

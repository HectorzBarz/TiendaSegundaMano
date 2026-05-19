<script setup lang="ts">
import { Category } from "@/types";
import BaseHoverCard from "./BaseHoverCard.vue";

const props = defineProps<{
    category: Category;
}>();
</script>
<template>
    <BaseHoverCard :to="'/articles/' + category.id">
        <div
            class="group relative flex min-h-60 w-full items-center justify-center overflow-hidden hover:cursor-pointer"
        >
            <!-- Imagen -->
            <img
                :src="category.img"
                alt="img"
                class="absolute inset-0 h-full w-full object-cover opacity-40 transition-all group-hover:scale-115"
            />

            <!-- Texto -->
            <span
                class="relative z-10 text-xl font-semibold transition-all group-hover:text-2xl"
            >
                {{ category.name }}
            </span>
        </div>
    </BaseHoverCard>
</template>

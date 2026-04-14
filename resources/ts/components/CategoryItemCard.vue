<script setup lang="ts">
import { Category } from "@/types";

const props = defineProps<{
    category: Category;
}>();
</script>
<template>
    <div
        class="group relative flex min-h-60 w-full items-center justify-center overflow-hidden border-y border-r border-gray-200 hover:cursor-pointer"
    >
        <RouterLink :to="'/articles/' + category.id">
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
        </RouterLink>
    </div>
</template>

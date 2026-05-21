<script setup lang="ts">
import { onMounted, ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { Category } from "@/types";
import CategoryItemCard from "@/components/CategoryItemCard.vue";

const categories = ref<Category[]>([]);
const isLoading = ref(true);

const store = useArticleFiltersStore();
const router = useRouter();

onMounted(async () => {
    try {
        // Obtenemos todas las categorías (sin limit)
        const response = await axios.get(
            "http://localhost:8000/api/categories",
        );
        categories.value = response.data;
    } catch (error) {
        console.error("Error al cargar las categorías:", error);
    } finally {
        isLoading.value = false;
    }
});

function selectCategory(id: number) {
    store.setCategory(id);

    router.push({
        name: "articles",
        query: { category: String(id) },
    });
}
</script>

<template>
    <div class="bg-fondo flex h-full flex-col gap-5">
        <!-- Title -->
        <div class="my-5 text-center align-middle">
            <h1 class="text-rojo-fuerte text-5xl font-bold">Categorías</h1>
            <p class="text-texto-secundario mt-2">
                Explora todas las categorías disponibles
            </p>
        </div>

        <!-- Loading state -->
        <div v-if="isLoading" class="py-10 text-center">
            Cargando categorías...
        </div>

        <!-- Category Grid -->
        <div
            v-else
            class="grid h-full w-full gap-2 overflow-hidden px-2 pb-5 text-center align-middle sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5"
        >
            <div v-for="category in categories" :key="category.id">
                <div @click="selectCategory(category.id)">
                    <CategoryItemCard
                        :category="category"
                        class="bg-card border-borde text-texto cursor-pointer"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

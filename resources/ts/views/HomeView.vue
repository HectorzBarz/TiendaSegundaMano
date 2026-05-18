<script setup lang="ts">
import CategoryItemCard from "@/components/CategoryItemCard.vue";
import hampter from "/storage/app/public/img/hampter.jpg";
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { useRouter } from "vue-router";

const categories = [
    { id: 1, img: hampter, name: "Muebles" },
    { id: 2, img: hampter, name: "Consolas" },
    { id: 3, img: hampter, name: "Cubertería" },
    { id: 4, img: hampter, name: "Deportes" },
    { id: 5, img: hampter, name: "Informática" },
    { id: 6, img: hampter, name: "Juegos de mesa" },
    { id: 5, img: hampter, name: "Patinetes" },
    { id: 6, img: hampter, name: "Ropa" },
];

const store = useArticleFiltersStore();
const router = useRouter();

function selectCategory(id: number) {
    store.setCategory(id);
    router.push("/articles");
}
</script>

<template>
    <div class="bg-fondo flex h-full flex-col gap-5">
        <!-- Slider -->
        <div
            class="bg-amarillo/20 border-borde text-rojo-fuerte flex h-96 items-center justify-center rounded-2xl border"
        >
            <p class="text-xl font-semibold">
                Hola caracola, esto será un slider
            </p>
        </div>
        <!-- END Slider -->

        <!-- Category Selector -->
        <div
            class="mb-5 grid h-full text-center align-middle md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
        >
            <div v-for="category in categories" :key="category.id">
                <RouterLink to="/articles" @click="selectCategory(category.id)">
                    <CategoryItemCard
                        :category="category"
                        class="bg-card border-borde"
                    />
                </RouterLink>
            </div>
        </div>
        <!-- END Category Selector -->
    </div>
</template>

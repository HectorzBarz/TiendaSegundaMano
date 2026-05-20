<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";
import StockBarChart from "@/components/StockBarChart.vue";
import hampter from "/storage/app/public/img/hampter.jpg";

import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import Button from "@volt/Button.vue";

import { useArticleFiltersStore } from "@/stores/articleFilters";
import { useAuthStore } from "@/stores/auth";

import { RouterLink, useRouter } from "vue-router";
import { computed, onMounted, ref } from "vue";
import { Article, Category } from "@/types";
import SelectButton from "@volt/SelectButton.vue";
import ArticlesDataTable from "@/components/ArticlesDataTable.vue";
import CategoriesDataTable from "@/components/CategoriesDataTable.vue";
import UsersDataTable from "@/components/UsersDataTable.vue";
import type { User } from "@/stores/auth";
import axios from "axios";

// Instancias del Store y Router
const store = useArticleFiltersStore();
const auth = useAuthStore();
const router = useRouter();

// Redirección si el usuario no está autenticado
if (!auth.user?.is_admin) {
    router.push("account");
}

const options = [
    { name: "Artículos", value: "articles", component: ArticlesDataTable },
    { name: "Categorías", value: "categories", component: CategoriesDataTable },
    { name: "Usuarios", value: "users", component: UsersDataTable },
];

const selectedOption = ref(options[0]);

const isChartCollapsed = ref(true);

const articles = <Article[]>[
    {
        id: 1,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 1",
        onSale: true,
        categoryId: 1,
        stock: 0,
        sell_count: 10,
    },
    {
        id: 2,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 2 ",
        onSale: false,
        categoryId: 2,
        stock: 2,
        sell_count: 5,
    },
    {
        id: 3,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 3",
        onSale: false,
        categoryId: 1,
        stock: 1,
        sell_count: 6,
    },
    {
        id: 4,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 4",
        onSale: true,
        categoryId: 3,
        stock: 7,
        sell_count: 2,
    },
    {
        id: 5,
        img: hampter,
        price: 10,
        name: "Artículo 5",
        onSale: false,
        categoryId: 1,
        stock: 1,
        sell_count: 1,
    },
    {
        id: 6,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 6",
        onSale: true,
        categoryId: 4,
        stock: 8,
        sell_count: 9,
    },
    {
        id: 7,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 7",
        onSale: false,
        categoryId: 6,
        stock: 2,
        sell_count: 7,
    },
    {
        id: 8,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 8",
        onSale: false,
        categoryId: 5,
        stock: 3,
        sell_count: 5,
    },
    {
        id: 9,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 9",
        onSale: false,
        categoryId: 6,
        stock: 1,
        sell_count: 10,
    },
    {
        id: 10,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 10",
        onSale: false,
        categoryId: 2,
        stock: 11,
        sell_count: 11,
    },
    {
        id: 1,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 11",
        onSale: true,
        categoryId: 1,
        stock: 10,
        sell_count: 15,
    },
    {
        id: 2,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 12 ",
        onSale: false,
        categoryId: 2,
        stock: 2,
        sell_count: 1,
    },
    {
        id: 3,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 13",
        onSale: false,
        categoryId: 1,
        stock: 1,
        sell_count: 6,
    },
    {
        id: 4,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 14",
        onSale: true,
        categoryId: 3,
        stock: 7,
        sell_count: 6,
    },
    {
        id: 5,
        img: hampter,
        price: 10,
        name: "Artículo 15",
        onSale: false,
        categoryId: 1,
        stock: 1,
        sell_count: 7,
    },
    {
        id: 6,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 16",
        onSale: true,
        categoryId: 4,
        stock: 8,
        sell_count: 1,
    },
    {
        id: 7,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 17",
        onSale: false,
        categoryId: 6,
        stock: 2,
        sell_count: 1,
    },
    {
        id: 8,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 18",
        onSale: false,
        categoryId: 5,
        stock: 3,
        sell_count: 8,
    },
    {
        id: 9,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 19",
        onSale: false,
        categoryId: 6,
        stock: 1,
        sell_count: 2,
    },
    {
        id: 10,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 20",
        onSale: false,
        categoryId: 2,
        stock: 11,
        sell_count: 4,
    },
];
const categories = ref<Category[]>([]);
const users = ref<User[]>([]);

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

const mockArticles = ref([{ id: 1, name: "Artículo 1", price: 10, stock: 5 }]);

// CATEGORIES
const categoriesLoading = ref(false);

const fetchCategories = async () => {
    try {
        categoriesLoading.value = true;
        const response = await axios.get("api/categories");

        console.log("Respuesta completa de la API:", response.data); // <--- ESTO ES CLAVE

        // Prueba esta lógica más flexible:
        // Si response.data es un array, lo usa.
        // Si response.data.data es un array, usa eso.
        if (Array.isArray(response.data)) {
            categories.value = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            categories.value = response.data.data;
        } else {
            console.warn(
                "La estructura de datos no es un array:",
                response.data,
            );
            categories.value = [];
        }
    } catch (e) {
        console.error("Error al cargar categorías", e);
        categories.value = [];
    } finally {
        categoriesLoading.value = false;
    }
};

// USERS
const usersLoading = ref(false);

const fetchUsers = async () => {
    try {
        usersLoading.value = true;

        users.value = await auth.getUsers();
    } catch (e) {
        console.error("Error fetching users", e);
    } finally {
        usersLoading.value = false;
    }
};

const getData = () => {
    // Si no hay datos, retornamos un array vacío explícitamente
    if (selectedOption.value.value === "articles")
        return filteredArticles.value || [];

    if (selectedOption.value.value === "categories")
        return Array.isArray(categories.value) ? categories.value : [];

    return Array.isArray(users.value) ? users.value : [];
};

onMounted(() => {
    fetchUsers();
    fetchCategories();
});
</script>

<template>
    <div class="bg-fondo min-h-screen p-6">
        <!-- HEADER -->
        <div
            class="bg-card border-borde mb-8 flex flex-col gap-5 rounded-3xl border p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between"
        >
            <div>
                <h1 class="text-rojo-fuerte text-4xl font-bold tracking-tight">
                    Panel de artículos
                </h1>

                <p class="text-texto-secundario mt-2">
                    Gestiona productos, precios y stock de forma rápida.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <RouterLink to="/admin/create" class="w-full sm:w-auto">
                    <Button
                        label="Nuevo artículo"
                        icon="pi pi-plus"
                        class="bg-azul! hover:bg-azul/90! w-full rounded-2xl border-0 px-5 py-3 text-white transition sm:w-auto"
                    />
                </RouterLink>

                <RouterLink to="/admin/category" class="w-full sm:w-auto">
                    <Button
                        label="Nueva categoría"
                        icon="pi pi-plus"
                        class="bg-naranja! hover:bg-naranja/90! w-full rounded-2xl border-0 px-5 py-3 text-white transition sm:w-auto"
                    />
                </RouterLink>

                <Button
                    icon="pi pi-refresh"
                    class="border-borde! text-rojo-fuerte! hover:bg-amarillo/20! w-full border bg-white px-4 py-3 transition sm:w-auto"
                />
            </div>
        </div>

        <!-- STATS -->
        <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-3">
            <!-- TOTAL -->
            <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-texto-secundario text-sm font-medium">
                            Total artículos
                        </p>

                        <h2 class="text-rojo-fuerte mt-2 text-4xl font-bold">
                            {{ filteredArticles.length }}
                        </h2>
                    </div>

                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100"
                    >
                        <i class="pi pi-box text-azul text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- OFERTA -->
            <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-texto-secundario text-sm font-medium">
                            En oferta
                        </p>

                        <h2 class="text-rojo-claro mt-2 text-4xl font-bold">
                            {{
                                filteredArticles.filter((a) => a.onSale).length
                            }}
                        </h2>
                    </div>

                    <div
                        class="bg-naranja/15 flex h-14 w-14 items-center justify-center rounded-2xl"
                    >
                        <i class="pi pi-tag text-naranja text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- STOCK -->
            <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-texto-secundario text-sm font-medium">
                            Stock bajo
                        </p>

                        <h2 class="text-naranja mt-2 text-4xl font-bold">
                            {{
                                filteredArticles.filter(
                                    (a) => (a.stock ?? 0) < 10,
                                ).length
                            }}
                        </h2>
                    </div>

                    <div
                        class="bg-amarillo/40 flex h-14 w-14 items-center justify-center rounded-2xl"
                    >
                        <i
                            class="pi pi-exclamation-circle text-naranja text-2xl"
                        ></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRÁFICO -->
        <section
            class="bg-card border-borde mb-8 overflow-hidden rounded-3xl border shadow-sm"
        >
            <!-- HEADER -->
            <button
                @click="isChartCollapsed = !isChartCollapsed"
                class="hover:bg-fondo/60 flex w-full items-center justify-between p-6 transition hover:cursor-pointer"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="bg-azul/10 flex h-12 w-12 items-center justify-center rounded-2xl"
                    >
                        <i class="pi pi-chart-bar text-azul text-2xl"></i>
                    </div>

                    <div class="text-left">
                        <h2 class="text-rojo-fuerte text-xl font-bold">
                            Estadísticas de stock
                        </h2>

                        <p class="text-texto-secundario text-sm">
                            Visualización del stock actual de productos
                        </p>
                    </div>
                </div>

                <!-- ICON -->
                <i
                    :class="
                        isChartCollapsed
                            ? 'pi pi-chevron-down'
                            : 'pi pi-chevron-up'
                    "
                    class="text-texto-secundario text-xl transition"
                ></i>
            </button>

            <!-- CONTENT -->
            <transition
                enter-active-class="transition-all duration-300 ease-in-out"
                leave-active-class="transition-all duration-300 ease-in-out"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-[1000px] opacity-100"
                leave-from-class="max-h-[1000px] opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div v-show="!isChartCollapsed" class="px-6 pb-6">
                    <StockBarChart :articles="filteredArticles" />
                </div>
            </transition>
        </section>

        <!-- CONTENT -->
        <section class="flex flex-col gap-6 lg:flex-row">
            <div class="bg-fondo min-h-screen p-6">
                <div class="mb-6 flex justify-center lg:justify-start">
                    <SelectButton
                        v-model="selectedOption"
                        :options="options"
                        optionLabel="name"
                        class="border-borde inline-flex w-full rounded-2xl border bg-white p-1 shadow-sm sm:w-auto"
                    />
                </div>

                <section class="flex flex-col items-start gap-6 lg:flex-row">
                    <div
                        class="bg-card border-borde shrink-0 rounded-3xl border p-6 shadow-sm lg:w-[320px]"
                    >
                        <h2 class="text-rojo-fuerte mb-4 text-xl font-semibold">
                            Filtros
                        </h2>
                        <FilterBase :suggestions="articles" />
                    </div>

                    <div
                        class="bg-card border-borde w-full grow overflow-hidden rounded-3xl border shadow-sm"
                    >
                        <component
                            :is="selectedOption.component"
                            :data="getData()"
                        />
                    </div>
                </section>
            </div>
        </section>
    </div>
</template>

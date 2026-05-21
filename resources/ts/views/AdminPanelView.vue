<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";
import StockBarChart from "@/components/StockBarChart.vue";

import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import Button from "@volt/Button.vue";

import { useArticleFiltersStore } from "@/stores/articleFilters";
import { useAuthStore } from "@/stores/auth";

import { RouterLink, useRouter } from "vue-router";
import { computed, onMounted, ref } from "vue";
import type { Article, RawArticle, ApiResponse, Category, User } from "@/types";
import SelectButton from "@volt/SelectButton.vue";
import ArticlesDataTable from "@/components/ArticlesDataTable.vue";
import CategoriesDataTable from "@/components/CategoriesDataTable.vue";
import UsersDataTable from "@/components/UsersDataTable.vue";
import OrdersDataTable from "@/components/OrdersDataTable.vue";
import { api } from "@/stores/auth";
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
    { name: "Pedidos", value: "orders", component: OrdersDataTable },
];

const selectedOption = ref(options[0]);

const isChartCollapsed = ref(true);

// Estado reactivo para artículos, ahora iniciamos con un array vacío
const articles = ref<Article[]>([]);
const categories = ref<Category[]>([]);
const users = ref<User[]>([]);
const orders = ref<any[]>([]);

// Propiedad computada para filtrar artículos
const filteredArticles = computed(() => {
    return articles.value.filter((article) => {
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

// ARTICLES
const articlesLoading = ref(false);

const fetchArticles = async (): Promise<void> => {
    try {
        articlesLoading.value = true;

        // Tipamos la respuesta: puede ser el array directo o el objeto envuelto
        const response = await axios.get<
            RawArticle[] | ApiResponse<RawArticle[]>
        >("api/articles");

        // 1. Extraer datos brutos con seguridad de tipos
        let rawData: RawArticle[] = [];
        if (Array.isArray(response.data)) {
            rawData = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            rawData = response.data.data;
        }

        // 2. Configurar URL base
        const baseUrl = axios.defaults.baseURL
            ? axios.defaults.baseURL.replace(/\/api\/?$/, "")
            : "http://localhost:8000";

        // 3. Procesar y mapear a tipo Article (UI)
        articles.value = rawData.map((art: RawArticle): Article => {
            let firstImagePath = "";

            // Lógica de extracción de imagen tipada
            if (Array.isArray(art.images) && art.images.length > 0) {
                firstImagePath = art.images[0];
            } else if (typeof art.images === "string") {
                try {
                    const parsed = JSON.parse(art.images) as string[];
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        firstImagePath = parsed[0];
                    }
                } catch {
                    firstImagePath = art.images;
                }
            }

            const imgUrl = firstImagePath
                ? firstImagePath.startsWith("http")
                    ? firstImagePath
                    : `${baseUrl}/storage/${firstImagePath}`
                : "https://placehold.co/600x400?text=Sin+Imagen";

            // Retornamos el objeto cumpliendo la interfaz Article
            return {
                ...art,
                // Mapeo de snake_case a camelCase si el backend no lo hace
                onSale: Boolean(art.on_sale),
                oldPrice: art.old_price,
                img: imgUrl,
            };
        });
    } catch (e) {
        console.error("Error al cargar artículos", e);
        articles.value = [];
    } finally {
        articlesLoading.value = false;
    }
};

// CATEGORIES
const categoriesLoading = ref(false);

const fetchCategories = async () => {
    try {
        categoriesLoading.value = true;
        const response = await axios.get("api/categories");

        if (Array.isArray(response.data)) {
            categories.value = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            categories.value = response.data.data;
        } else {
            console.warn(
                "La structure de datos no es un array:",
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

// ORDERS
const ordersLoading = ref(false);

const fetchOrders = async () => {
    try {
        ordersLoading.value = true;
        const response = await api.get("/admin/orders");
        orders.value = Array.isArray(response.data) ? response.data : [];
    } catch (e) {
        console.error("Error fetching orders", e);
        orders.value = [];
    } finally {
        ordersLoading.value = false;
    }
};

const getData = () => {
    if (selectedOption.value.value === "articles")
        return filteredArticles.value || [];

    if (selectedOption.value.value === "categories")
        return Array.isArray(categories.value) ? categories.value : [];

    if (selectedOption.value.value === "orders")
        return Array.isArray(orders.value) ? orders.value : [];

    return Array.isArray(users.value) ? users.value : [];
};

const handleDelete = () => {
    const active = selectedOption.value.value;

    if (active === "users") {
        fetchUsers();
    } else if (active === "categories") {
        fetchCategories();
    } else if (active === "articles") {
        fetchArticles(); // Recargar los artículos al borrar uno
    } else if (active === "orders") {
        fetchOrders();
    }
};

// Cargar todos los datos al montar la vista
onMounted(() => {
    fetchArticles();
    fetchUsers();
    fetchCategories();
    fetchOrders();
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
                    @click="handleDelete"
                    class="border-borde! text-rojo-fuerte! hover:bg-amarillo/20! w-full border bg-white px-4 py-3 transition sm:w-auto"
                    title="Actualizar datos"
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
                            <!-- Si está cargando, mostramos un indicador de carga -->
                            <span
                                v-if="articlesLoading"
                                class="pi pi-spinner pi-spin text-2xl"
                            ></span>
                            <span v-else>{{ filteredArticles.length }}</span>
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
                            <span
                                v-if="articlesLoading"
                                class="pi pi-spinner pi-spin text-2xl"
                            ></span>
                            <span v-else>
                                {{
                                    filteredArticles.filter(
                                        (a) => a.onSale || a.onSale,
                                    ).length
                                }}
                            </span>
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
                            <span
                                v-if="articlesLoading"
                                class="pi pi-spinner pi-spin text-2xl"
                            ></span>
                            <span v-else>
                                {{
                                    filteredArticles.filter(
                                        (a) => (a.stock ?? 0) < 10,
                                    ).length
                                }}
                            </span>
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
                    <!-- Evitamos pasar arrays vacios mientas carga para prevenir fallos en el Canvas del grafico -->
                    <StockBarChart
                        v-if="!articlesLoading && filteredArticles.length > 0"
                        :articles="filteredArticles"
                    />
                    <div
                        v-else-if="articlesLoading"
                        class="flex justify-center py-10"
                    >
                        <i class="pi pi-spinner pi-spin text-azul text-4xl"></i>
                    </div>
                </div>
            </transition>
        </section>

        <!-- CONTENT -->
        <section class="flex flex-col gap-6 lg:flex-row">
            <div class="bg-fondo min-w-full pb-6">
                <div class="mb-6 flex justify-center lg:justify-start">
                    <SelectButton
                        v-model="selectedOption"
                        :options="options"
                        optionLabel="name"
                        class="border-borde inline-flex w-full rounded-2xl border bg-white p-1 shadow-sm sm:w-auto"
                    />
                </div>

                <section class="flex flex-col items-start gap-6 lg:flex-row">
                    <!-- FILTROS SÓLO SI ESTAMOS EN ARTÍCULOS -->
                    <div
                        v-show="selectedOption.value === 'articles'"
                        class="bg-card border-borde shrink-0 rounded-3xl border p-6 shadow-sm lg:w-[320px]"
                    >
                        <h2 class="text-rojo-fuerte mb-4 text-xl font-semibold">
                            Filtros
                        </h2>
                        <!-- Se pasa el valor reactivo extraído para las sugerencias -->
                        <FilterBase :suggestions="articles" />
                    </div>

                    <div
                        class="bg-card border-borde w-full grow overflow-hidden rounded-3xl border shadow-sm"
                    >
                        <component
                            :is="selectedOption.component"
                            :data="getData()"
                            @deleted="handleDelete"
                        />
                    </div>
                </section>
            </div>
        </section>
    </div>
</template>

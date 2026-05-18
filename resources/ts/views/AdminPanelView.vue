<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";
import ArticleItemCard from "@/components/ArticleItemCard.vue";
import hampter from "/storage/app/public/img/hampter.jpg";

import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import Button from "@volt/Button.vue";
import DangerButton from "@volt/DangerButton.vue";
import ColumnGroup from "primevue/columngroup"; // optional
import Row from "primevue/row"; // optional

import { computed, ref } from "vue";
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { Article } from "@/types";
import StockBarChart from "@/components/StockBarChart.vue";
import { RouterLink } from "vue-router";
import SecondaryButton from "@volt/SecondaryButton.vue";

const isChartCollapsed = ref(true);

const store = useArticleFiltersStore();

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

const rowClass = (data: Article) => {
    return data.stock === 0 ? "bg-red-50/60 opacity-70 grayscale" : "";
};
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

            <div class="flex gap-3">
                <RouterLink to="/admin/create">
                    <Button
                        label="Nuevo artículo"
                        icon="pi pi-plus"
                        class="bg-azul! hover:bg-azul/90! rounded-2xl border-0 px-5 py-3 text-white transition"
                    />
                </RouterLink>

                <Button
                    icon="pi pi-refresh"
                    class="border-borde! text-rojo-fuerte! hover:bg-amarillo/20! rounded-2xl border bg-white px-4 py-3 transition"
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
            <!-- SIDEBAR -->
            <div
                class="bg-card border-borde rounded-3xl border p-6 shadow-sm lg:w-[320px]"
            >
                <div class="mb-6 flex items-center gap-3">
                    <div
                        class="bg-rojo-claro/10 flex h-11 w-11 items-center justify-center rounded-2xl"
                    >
                        <i class="pi pi-sliders-h text-rojo-claro"></i>
                    </div>

                    <div>
                        <h2 class="text-rojo-fuerte text-xl font-semibold">
                            Filtros
                        </h2>

                        <p class="text-texto-secundario text-sm">
                            Busca artículos fácilmente
                        </p>
                    </div>
                </div>

                <FilterBase :suggestions="articles" />
            </div>

            <!-- TABLE -->
            <div
                class="bg-card border-borde overflow-hidden rounded-3xl border shadow-sm"
            >
                <DataTable
                    :value="filteredArticles"
                    :rowClass="rowClass"
                    paginator
                    :rows="10"
                    stripedRows
                    responsiveLayout="scroll"
                    tableStyle="min-width: 1000px"
                >
                    <!-- IMAGE -->
                    <Column header="">
                        <template #body="{ data }">
                            <img
                                :src="data.img"
                                :alt="data.name"
                                class="border-borde h-16 w-16 rounded-2xl border object-cover"
                            />
                        </template>
                    </Column>

                    <!-- ID -->
                    <Column field="id" header="ID" sortable>
                        <template #body="{ data }">
                            <span
                                class="bg-rojo-fuerte/10 text-rojo-fuerte rounded-xl px-3 py-1 text-sm font-semibold"
                            >
                                #{{ data.id }}
                            </span>
                        </template>
                    </Column>

                    <!-- PRODUCT -->
                    <Column field="name" header="Producto">
                        <template #body="{ data }">
                            <div>
                                <p
                                    class="font-semibold"
                                    :class="
                                        data.stock === 0
                                            ? 'text-red-700 line-through'
                                            : 'text-texto'
                                    "
                                >
                                    {{ data.name }}
                                </p>

                                <p class="text-texto-secundario mt-1 text-sm">
                                    Categoría {{ data.categoryId }}
                                </p>
                            </div>
                        </template>
                    </Column>

                    <!-- PRICE -->
                    <Column field="price" header="Precio">
                        <template #body="{ data }">
                            <div class="flex items-center gap-2">
                                <span class="text-azul text-lg font-bold">
                                    {{ data.price }}€
                                </span>

                                <span
                                    v-if="data.oldPrice"
                                    class="text-texto-secundario text-sm line-through"
                                >
                                    {{ data.oldPrice }}€
                                </span>
                            </div>
                        </template>
                    </Column>

                    <!-- STOCK -->
                    <Column field="stock" header="Stock" sortable>
                        <template #body="{ data }">
                            <!-- SIN STOCK -->
                            <span
                                v-if="data.stock === 0"
                                class="flex items-center gap-2 rounded-full bg-red-100 px-4 py-2 text-xs font-bold text-red-700"
                            >
                                <i class="pi pi-times-circle"></i>
                                Sin stock
                            </span>

                            <!-- STOCK BAJO -->
                            <span
                                v-else-if="data.stock < 10"
                                class="rounded-full bg-amber-100 px-4 py-2 text-xs font-semibold text-amber-700"
                            >
                                {{ data.stock }} unidades
                            </span>

                            <!-- STOCK OK -->
                            <span
                                v-else
                                class="rounded-full bg-blue-100 px-4 py-2 text-xs font-semibold text-blue-700"
                            >
                                {{ data.stock }} unidades
                            </span>
                        </template>
                    </Column>

                    <!-- SELLED -->
                    <Column field="selled" header="Vendidos">
                        <template #body="{ data }">
                            <span
                                :class="
                                    data.stock < 10
                                        ? 'bg-amarillo/40 text-naranja'
                                        : 'bg-azul/10 text-azul'
                                "
                                class="rounded-full px-4 py-2 text-xs font-semibold"
                            >
                                {{ data.sell_count }} unidades
                            </span>
                        </template>
                    </Column>

                    <!-- STATUS -->
                    <Column field="onSale" header="Estado">
                        <template #body="{ data }">
                            <span
                                :class="
                                    data.onSale
                                        ? 'bg-naranja/15 text-rojo-claro'
                                        : 'bg-slate-100 text-slate-500'
                                "
                                class="rounded-full px-4 py-2 text-xs font-semibold"
                            >
                                {{ data.onSale ? "En oferta" : "Normal" }}
                            </span>
                        </template>
                    </Column>

                    <!-- ACTIONS -->
                    <Column header="Acciones">
                        <template #body="{ data }">
                            <div class="flex gap-2">
                                <!-- EDIT -->
                                <RouterLink
                                    :to="'/admin/articles/' + data.id + '/edit'"
                                >
                                    <Button
                                        icon="pi pi-pencil"
                                        :disabled="data.stock === 0"
                                        class="bg-azul! hover:bg-azul/90! h-11 w-11 rounded-2xl border-0 text-white! transition"
                                    />
                                </RouterLink>

                                <!-- DELETE -->
                                <Button
                                    icon="pi pi-trash"
                                    class="bg-rojo-claro hover:bg-rojo-fuerte! h-11 w-11 rounded-2xl border-0 text-white transition"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import hampter from "/storage/app/public/img/hampter.jpg";

import { computed, onMounted, ref, watch } from "vue";
import { ApiResponse, Article, Category } from "@/types";

import AutoComplete from "@volt/AutoComplete.vue";
import InputNumber from "@volt/InputNumber.vue";
import ToggleSwitch from "@volt/ToggleSwitch.vue";
import Select from "@volt/Select.vue";
import Button from "@volt/Button.vue";
import DangerButton from "@volt/DangerButton.vue";
import { useArticleFiltersStore } from "@/stores/articleFilters";
import { api } from "@/stores/auth";

const props = defineProps<{
    suggestions: Article[];
}>();

const emit = defineEmits<{
    (
        e: "submit",
        filters: {
            categoryId: number | null;
            articleName: string;
            minPrice: number;
            maxPrice: number;
            onSale: boolean;
        },
    ): void;
}>();

const store = useArticleFiltersStore();

// STATE
const articleName = ref("");
const filteredArticles = ref<any[]>([]);

const minPrice = ref(0);
const maxPrice = ref(0);
const onSale = ref(false);

const categories = ref<Category[]>([]);

const loadCategories = async () => {
    try {
        const response = await api.get<ApiResponse<Category[]>>("/categories");

        const data = Array.isArray(response.data)
            ? response.data
            : response.data.data;

        categories.value = data;
    } catch (error) {
        console.error("Error cargando categorías:", error);
    }
};

const categoryOptions = computed(() =>
    categories.value.map((c) => ({
        label: c.name,
        value: c.id,
    })),
);

const selectedCategoryId = ref<number | null>(null);

// AUTOCOMPLETE
const search = (event: any) => {
    if (!event.query) {
        filteredArticles.value = [];
        return;
    }

    filteredArticles.value = props.suggestions.filter((article) =>
        article.name.toLowerCase().includes(event.query.toLowerCase()),
    );
};

// PRICE LIMITS
const minAvailablePrice = computed(() => {
    if (!props.suggestions.length) return 0;
    return Math.min(...props.suggestions.map((a) => Number(a.price)));
});

const maxAvailablePrice = computed(() => {
    if (!props.suggestions.length) return 0;
    return Math.max(...props.suggestions.map((a) => Number(a.price)));
});

function onSelect(event: any) {
    articleName.value = event.value.name;
}

function submit() {
    store.setFilters({
        categoryId: selectedCategoryId.value,
        articleName: articleName.value,
        minPrice: minPrice.value,
        maxPrice: maxPrice.value,
        onSale: onSale.value,
    });
}

// RESET
function reset() {
    articleName.value = "";
    selectedCategoryId.value = null;
    onSale.value = false;

    minPrice.value = minAvailablePrice.value;
    maxPrice.value = maxAvailablePrice.value;

    store.reset();
}

onMounted(() => {
    reset();
    loadCategories();
});
</script>

<template>
    <div
        class="top-0 h-fit w-full bg-gray-100 p-2 md:mx-auto lg:sticky lg:h-screen"
    >
        <div
            class="flex flex-col gap-5 sm:grid sm:grid-cols-4 md:place-items-center md:gap-4 lg:grid-cols-5"
        >
            <!-- NAME -->
            <div
                class="flex w-full items-center gap-2 sm:col-span-2 lg:col-span-5 lg:justify-start"
            >
                <AutoComplete
                    v-model="articleName"
                    inputId="name"
                    optionLabel="name"
                    placeholder="Nombre"
                    class="w-full"
                    :suggestions="filteredArticles"
                    @complete="search"
                    inputClass="w-full"
                    @item-select="onSelect"
                />
            </div>

            <!-- CATEGORY -->
            <div
                class="flex w-full items-center gap-2 sm:col-span-2 lg:col-span-5 lg:justify-start"
            >
                <Select
                    v-model="selectedCategoryId"
                    :options="categoryOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Categoría"
                    class="w-full"
                />
            </div>

            <!-- PRICE -->
            <div
                class="flex w-full gap-2 sm:col-span-3 md:col-span-2 md:justify-center md:gap-5 lg:col-span-5 lg:gap-2"
            >
                <div
                    class="flex w-1/2 items-center gap-2 md:w-auto lg:w-1/2 lg:flex-col xl:flex-row"
                >
                    <span>Min: </span>
                    <InputNumber
                        v-model="minPrice"
                        inputId="price-min"
                        :maxFractionDigits="1"
                        :min="minAvailablePrice"
                        :max="maxPrice"
                        fluid
                        class="w-full md:w-24"
                    />
                </div>

                <div
                    class="flex w-1/2 items-center gap-2 md:w-auto lg:w-1/2 lg:flex-col xl:flex-row"
                >
                    <span>Max: </span>
                    <InputNumber
                        v-model="maxPrice"
                        inputId="price-max"
                        :maxFractionDigits="1"
                        :min="minPrice"
                        :max="maxAvailablePrice"
                        fluid
                        class="w-full md:w-24"
                    />
                </div>
            </div>

            <!-- SALE -->
            <div
                class="flex w-full items-center gap-2 sm:col-span-1 md:justify-center lg:col-span-2 lg:justify-start xl:col-span-5"
            >
                <span>En oferta: </span>
                <ToggleSwitch v-model="onSale" />
            </div>

            <!-- SEARCH / RESET -->
            <div
                class="flex h-full w-full gap-2 sm:col-span-4 md:col-span-1 lg:col-span-3 xl:col-span-5"
            >
                <Button class="pi pi-search w-1/2 text-white" @click="submit" />
                <DangerButton
                    class="pi pi-eraser w-1/2 text-white"
                    @click="reset"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import hampter from "/storage/app/public/img/hampter.jpg";

import { useRoute, useRouter } from "vue-router";
import { computed, onMounted, ref } from "vue";

import { Article, Category } from "@/types";

import AutoComplete from "@volt/AutoComplete.vue";
import InputNumber from "@volt/InputNumber.vue";
import ToggleSwitch from "@volt/ToggleSwitch.vue";
import Select from "@volt/Select.vue";
import Button from "@volt/Button.vue";
import DangerButton from "@volt/DangerButton.vue";

const props = defineProps<{
    suggestions: Article[];
}>();

const route = useRoute();
const router = useRouter();

const articleName = ref("");
const filteredArticles = ref<any[]>([]);

const search = (event: any) => {
    if (!event.query) {
        filteredArticles.value = props.suggestions;
        return;
    }

    filteredArticles.value = props.suggestions.filter((article) =>
        article.name.toLowerCase().includes(event.query.toLowerCase()),
    );
};

const minPrice = ref(0);
const maxPrice = ref(0);

const onSale = ref(false);

const selectedCategory = ref<Category | null>(null);
const categories = ref<Category[]>([
    {
        id: 1,
        img: hampter,
        name: "Juguetes",
    },
    {
        id: 2,
        img: hampter,
        name: "Informática",
    },
    {
        id: 3,
        img: hampter,
        name: "Juegos de mesa",
    },
    {
        id: 4,
        img: hampter,
        name: "Electrodomesticos",
    },
    {
        id: 5,
        img: hampter,
        name: "Muebles",
    },
    {
        id: 6,
        img: hampter,
        name: "Patinetes",
    },
    {
        id: 7,
        img: hampter,
        name: "Ropa",
    },
    {
        id: 8,
        img: hampter,
        name: "Videojuegos",
    },
    {
        id: 9,
        img: hampter,
        name: "Películas",
    },
    {
        id: 10,
        img: hampter,
        name: "Reproductores de música",
    },
]);

const filters = ref({
    articleName: articleName.value,
    minPrice: minPrice.value,
    maxPrice: maxPrice.value,
    onSale: onSale.value,
    category: selectedCategory.value ?? "",
});

function checkCategory() {
    const categoryId = Number(route.params.categoryId);

    if (!categoryId) return;

    const foundCategory = categories.value.find((cat) => cat.id === categoryId);

    if (foundCategory) {
        selectedCategory.value = foundCategory;
    }
}

const minAvailablePrice = computed(() => {
    if (!props.suggestions.length) return 0;
    return Math.min(...props.suggestions.map((a) => Number(a.price)));
});

const maxAvailablePrice = computed(() => {
    if (!props.suggestions.length) return 0;
    return Math.max(...props.suggestions.map((a) => Number(a.price)));
});

function submit() {
    window.location.reload();
}

async function reset() {
    articleName.value = "";
    selectedCategory.value = null;
    onSale.value = false;
    filteredArticles.value = props.suggestions;

    await router.push({ name: "articles" });

    minPrice.value = minAvailablePrice.value;
    maxPrice.value = maxAvailablePrice.value;
}

onMounted(() => {
    minPrice.value = minAvailablePrice.value;
    maxPrice.value = maxAvailablePrice.value;

    checkCategory();
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
                />
            </div>

            <!-- CATEGORY -->
            <div
                class="flex w-full items-center gap-2 sm:col-span-2 lg:col-span-5 lg:justify-start"
            >
                <Select
                    v-model="selectedCategory"
                    editable
                    :options="categories"
                    optionLabel="name"
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

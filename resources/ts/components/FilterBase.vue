<script setup lang="ts">
import { ref } from "vue";

import { Article } from "@/types";

import AutoComplete from "@volt/AutoComplete.vue";
import InputNumber from "@volt/InputNumber.vue";
import ToggleSwitch from "@volt/ToggleSwitch.vue";
import Select from "@volt/Select.vue";
import Button from "@volt/Button.vue";
import DangerButton from "@volt/DangerButton.vue";

const props = defineProps<{
    suggestions: Article[];
}>();

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

const minPrice = ref(getMinPrice());
const maxPrice = ref(getMaxPrice());

const onSale = ref(false);

const selectedCategory = ref("");
const categories = ref([
    { name: "Juegetes", code: "jueguetes" },
    { name: "Informática", code: "informatica" },
    { name: "Ropa", code: "ropa" },
    { name: "Muebles", code: "muebles" },
    { name: "Juegos de Mesa", code: "juegos_mesa" },
]);

const filters = ref({
    articleName: articleName.value ?? "",
    minPrice: minPrice.value ?? getMinPrice(),
    maxPrice: maxPrice.value ?? getMaxPrice(),
    onSale: onSale.value ?? false,
    category: selectedCategory.value ?? "",
});

function getMinPrice() {
    if (props.suggestions.length === 0) return 0;
    return Math.min(...props.suggestions.map((article) => article.price));
}

function getMaxPrice() {
    if (props.suggestions.length === 0) return 0;
    return Math.max(...props.suggestions.map((article) => article.price));
}

function submit() {
    window.location.reload();
}

function reset() {
    articleName.value = "";
    selectedCategory.value = "";
    minPrice.value = getMinPrice();
    maxPrice.value = getMaxPrice();
    onSale.value = false;
    filteredArticles.value = props.suggestions;
}
</script>

<template>
    <div class="w-full bg-gray-200 p-2 md:mx-auto">
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
                        inputId="price"
                        :min="0"
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
                        inputId="price"
                        :max="100"
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

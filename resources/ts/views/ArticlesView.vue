<script setup lang="ts">
import FilterBase from "@/components/FilterBase.vue";
import hampter from "@/img/hampter.jpg";

const articles = [
    {
        id: 1,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 1",
        onSale: true,
    },
    {
        id: 2,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 2 ",
        onSale: false,
    },
    {
        id: 3,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 3",
        onSale: false,
    },
    {
        id: 4,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 4",
        onSale: true,
    },
    {
        id: 5,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 5",
        onSale: false,
    },
    {
        id: 6,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 6",
        onSale: true,
    },
    {
        id: 7,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 7",
        onSale: false,
    },
    {
        id: 8,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 8",
        onSale: false,
    },
    {
        id: 9,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 9",
        onSale: false,
    },
    {
        id: 10,
        img: hampter,
        oldPrice: 1,
        price: 0.1,
        name: "Artículo 10",
        onSale: false,
    },
];
</script>

<template>
    <div class="flex h-full flex-col gap-5">
        <!-- Title -->
        <div class="my-5 text-center align-middle">
            <h1 class="text-5xl">Artículos</h1>
        </div>
        <!-- END Title -->

        <section class="flex flex-col lg:flex-row">
            <!-- Article filters -->

            <FilterBase :suggestions="articles" class="xl:max-w-1/5" />

            <!-- END Article filters -->

            <!-- Article List -->
            <div
                class="mx-5 mb-5 grid h-full gap-3 text-center align-middle sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5"
            >
                <div
                    v-for="article in articles"
                    :key="article.id"
                    class="flex min-h-1/4 w-full items-center justify-center overflow-hidden rounded-t-xl hover:cursor-pointer hover:shadow-lg"
                >
                    <div>
                        <!-- Image -->
                        <img
                            :src="article.img"
                            alt="img"
                            class="h-full w-full"
                        />

                        <!-- Article name -->
                        <h2
                            class="text-xl font-semibold transition-all group-hover:text-2xl"
                        >
                            {{ article.name }}
                        </h2>

                        <!-- Article price -->
                        <div>
                            <h3
                                class="flex justify-center gap-2 text-lg font-semibold transition-all group-hover:text-2xl"
                                :class="
                                    article.onSale
                                        ? 'text-red-600'
                                        : 'text-gray-600'
                                "
                            >
                                {{ article.price }}€
                                <span
                                    v-if="article.onSale"
                                    class="flex items-center text-sm text-gray-600 line-through"
                                >
                                    Antiguamente: {{ article.oldPrice }}€
                                </span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Article List -->
        </section>
    </div>
</template>

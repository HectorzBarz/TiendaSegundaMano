<script setup lang="ts">
import hampter from "/storage/app/public/img/hampter.jpg";
import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";

const orders = [
    {
        id: 1,
        name: "Hámster adorable",
        price: 0.1,
        date: "12/01/2026",
        delivered: false,
        img: hampter,
    },
    {
        id: 2,
        name: "Teclado mecánico",
        price: 25,
        date: "03/01/2026",
        delivered: true,
        img: hampter,
    },
    {
        id: 3,
        name: "Monitor 24”",
        price: 90,
        date: "28/12/2025",
        delivered: true,
        img: hampter,
    },
];
</script>

<template>
    <main class="bg-fondo min-h-screen px-6 py-10">
        <!-- TITLE -->
        <h1 class="text-rojo-fuerte mb-8 text-center text-4xl font-bold">
            Historial de compras
        </h1>

        <!-- EMPTY -->
        <div
            v-if="!orders.length"
            class="text-texto-secundario text-center text-lg"
        >
            No tienes compras todavía
        </div>

        <!-- TABLE -->
        <section v-else class="mx-auto max-w-6xl">
            <DataTable
                :value="orders"
                paginator
                :rows="10"
                stripedRows
                responsiveLayout="scroll"
                tableStyle="min-width: 900px"
                class="bg-card border-borde rounded-2xl border"
            >
                <!-- IMAGE -->
                <Column header="">
                    <template #body="{ data }">
                        <img
                            :src="data.img"
                            class="border-borde h-14 w-14 rounded-xl border object-cover"
                        />
                    </template>
                </Column>

                <!-- PRODUCT -->
                <Column field="name" header="Producto">
                    <template #body="{ data }">
                        <p class="text-texto font-semibold">
                            {{ data.name }}
                        </p>
                    </template>
                </Column>

                <!-- DATE -->
                <Column field="date" header="Fecha">
                    <template #body="{ data }">
                        <span class="text-texto-secundario">
                            {{ data.date }}
                        </span>
                    </template>
                </Column>

                <!-- PRICE -->
                <Column field="price" header="Precio">
                    <template #body="{ data }">
                        <span class="text-azul font-bold">
                            {{ data.price }} €
                        </span>
                    </template>
                </Column>

                <!-- STATUS -->
                <Column field="delivered" header="Estado">
                    <template #body="{ data }">
                        <span
                            class="rounded-full px-4 py-1 text-xs font-semibold"
                            :class="
                                data.delivered
                                    ? 'bg-azul/10 text-azul'
                                    : 'bg-amarillo/30 text-naranja'
                            "
                        >
                            {{ data.delivered ? "Entregado" : "Enviado" }}
                        </span>
                    </template>
                </Column>
            </DataTable>
        </section>
    </main>
</template>

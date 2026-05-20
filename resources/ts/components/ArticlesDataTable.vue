<script setup lang="ts">
import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import { Article } from "@/types";
defineProps<{ data: Article[] }>();

const rowClass = (data: Article) => {
    return data.stock === 0 ? "bg-red-50/60 opacity-70 grayscale" : "";
};
</script>

<template>
    <DataTable
        :value="data"
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
                    <RouterLink :to="'/admin/articles/' + data.id + '/edit'">
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
</template>

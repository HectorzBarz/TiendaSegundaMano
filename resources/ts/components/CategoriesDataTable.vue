<script setup lang="ts">
import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import { Category } from "@/types";
import Button from "@volt/Button.vue";
defineProps<{ data: Category[] }>();
</script>

<template>
    <DataTable
        :value="data"
        paginator
        :rows="10"
        stripedRows
        responsiveLayout="scroll"
        tableStyle="min-width: 1000px"
    >
        <!-- IMAGE -->
        <Column field="img" header="Imagen">
            <template #body="{ data }">
                <img
                    :src="data.img"
                    :alt="data.name"
                    class="border-borde h-16 w-16 rounded-2xl border object-cover"
                />
            </template>
        </Column>

        <!-- SELLED -->
        <Column field="id" header="ID"> </Column>

        <!-- STATUS -->
        <Column field="name" header="Nombre"> </Column>

        <!-- ACTIONS -->
        <Column field="actions" header="Acciones">
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

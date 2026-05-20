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
        <!-- CORRECCIÓN: Campo de imagen -->
        <Column header="Imagen">
            <template #body="{ data }">
                <!-- Verificamos que data.image exista para evitar errores de render -->
                <img
                    v-if="data.image"
                    :src="data.image"
                    :alt="data.name"
                    class="border-borde h-16 w-16 rounded-2xl border object-cover"
                />
            </template>
        </Column>

        <Column field="id" header="ID"></Column>
        <Column field="name" header="Nombre"></Column>

        <Column field="actions" header="Acciones">
            <template #body="{ data }">
                <div class="flex gap-2">
                    <!-- CORRECCIÓN: Asegúrate de que la ruta sea correcta -->
                    <RouterLink :to="`/admin/categories/${data.id}/edit`">
                        <Button
                            icon="pi pi-pencil"
                            class="bg-azul! h-11 w-11 rounded-2xl border-0 text-white!"
                        />
                    </RouterLink>
                    <Button
                        icon="pi pi-trash"
                        class="bg-rojo-claro hover:bg-rojo-fuerte! h-11 w-11 rounded-2xl border-0 text-white"
                    />
                </div>
            </template>
        </Column>
    </DataTable>
</template>

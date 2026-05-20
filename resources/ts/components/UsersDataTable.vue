<script setup lang="ts">
import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import { User } from "@/types";
import Button from "@volt/Button.vue";
import { useAuthStore } from "@/stores/auth";

defineProps<{ data: User[] }>();

const auth = useAuthStore();

const toggleAdmin = async (user: User) => {
    try {
        if (user.is_admin) {
            await auth.removeAdmin(user.id);
            user.is_admin = false;
        } else {
            await auth.makeAdmin(user.id);
            user.is_admin = true;
        }
    } catch (e) {
        console.error(e);
    }
};
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
                    :src="data.profile_image"
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
        <Column header="Acciones">
            <template #body="{ data }">
                <div class="flex gap-2">
                    <Button
                        :icon="data.is_admin ? 'pi pi-shield' : 'pi pi-user'"
                        :class="
                            data.is_admin
                                ? 'bg-naranja! hover:bg-naranja/90! h-11 w-11 rounded-2xl border-0 text-white transition'
                                : 'h-11 w-11 rounded-2xl border-0 bg-slate-500! text-white transition hover:bg-slate-600!'
                        "
                        @click="toggleAdmin(data)"
                    />

                    <RouterLink :to="'/admin/users/' + data.id + '/edit'">
                        <Button
                            icon="pi pi-pencil"
                            class="bg-azul! hover:bg-azul/90! h-11 w-11 rounded-2xl border-0 text-white! transition"
                        />
                    </RouterLink>

                    <Button
                        icon="pi pi-trash"
                        class="bg-rojo-claro hover:bg-rojo-fuerte! h-11 w-11 rounded-2xl border-0 text-white transition"
                    />
                </div>
            </template>
        </Column>
    </DataTable>
</template>

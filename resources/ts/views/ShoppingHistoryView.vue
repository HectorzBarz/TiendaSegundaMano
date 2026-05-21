<script setup lang="ts">
import { useAuthStore, api } from "@/stores/auth";
import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import type { Order } from "@/types";

const auth = useAuthStore();
const router = useRouter();

if (!auth.isAuthenticated) {
    router.push("login");
}

const orders = ref<Order[]>([]);
const loading = ref(false);

const statusLabels: Record<string, string> = {
    pending: "Pendiente",
    processing: "Procesando",
    completed: "Completado",
    delivered: "Entregado",
    cancelled: "Cancelado",
};

const statusColors: Record<string, string> = {
    pending: "bg-amarillo/30 text-naranja",
    processing: "bg-azul/10 text-azul",
    completed: "bg-verde/10 text-green-700",
    delivered: "bg-verde/10 text-green-700",
    cancelled: "bg-red-100 text-red-600",
};

async function fetchOrders() {
    try {
        loading.value = true;
        const response = await api.get("/orders");
        orders.value = response.data;
    } catch (e) {
        console.error("Error fetching orders", e);
        orders.value = [];
    } finally {
        loading.value = false;
    }
}

function formatDate(dateStr: string) {
    const d = new Date(dateStr);
    return d.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
    });
}

onMounted(fetchOrders);
</script>

<template>
    <main class="bg-fondo min-h-screen px-6 py-10">
        <h1 class="text-rojo-fuerte mb-8 text-center text-4xl font-bold">
            Historial de compras
        </h1>

        <div v-if="loading" class="flex justify-center py-20">
            <i class="pi pi-spinner pi-spin text-azul text-4xl"></i>
        </div>

        <div
            v-else-if="!orders.length"
            class="text-texto-secundario text-center text-lg"
        >
            No tienes compras todavía
        </div>

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
                <Column field="id" header="Pedido #" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold">#{{ data.id }}</span>
                    </template>
                </Column>

                <Column field="created_at" header="Fecha" :sortable="true">
                    <template #body="{ data }">
                        <span class="text-texto-secundario text-sm">
                            {{ formatDate(data.created_at) }}
                        </span>
                    </template>
                </Column>

                <Column header="Productos">
                    <template #body="{ data }">
                        <div class="flex flex-col gap-0.5 text-sm">
                            <span
                                v-for="item in data.items.slice(0, 3)"
                                :key="item.id"
                            >
                                {{ item.quantity }}x {{ item.article_name }}
                            </span>
                            <span
                                v-if="data.items.length > 3"
                                class="text-texto-secundario text-xs"
                            >
                                +{{ data.items.length - 3 }} más
                            </span>
                        </div>
                    </template>
                </Column>

                <Column field="total" header="Total" :sortable="true">
                    <template #body="{ data }">
                        <span class="text-azul font-bold">
                            {{ Number(data.total).toFixed(2) }} €
                        </span>
                    </template>
                </Column>

                <Column field="status" header="Estado" :sortable="true">
                    <template #body="{ data }">
                        <span
                            class="rounded-full px-4 py-1 text-xs font-semibold"
                            :class="
                                statusColors[data.status] ||
                                'bg-gray-100 text-gray-600'
                            "
                        >
                            {{ statusLabels[data.status] || data.status }}
                        </span>
                    </template>
                </Column>
            </DataTable>
        </section>
    </main>
</template>

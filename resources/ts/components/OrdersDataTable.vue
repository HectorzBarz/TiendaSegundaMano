<script setup lang="ts">
import DataTable from "@volt/DataTable.vue";
import Column from "primevue/column";
import Select from "@volt/Select.vue";
import { useAuthStore, api } from "@/stores/auth";
import { ref, onMounted, watch } from "vue";
import type { Order } from "@/types";

const props = defineProps<{
    data: Order[];
}>();

const emit = defineEmits(["deleted"]);

const auth = useAuthStore();
const orders = ref<Order[]>([]);

watch(
    () => props.data,
    (val) => {
        orders.value = val;
    },
    { immediate: true },
);

const statusOptions = [
    { label: "Pendiente", value: "pending" },
    { label: "Procesando", value: "processing" },
    { label: "Completado", value: "completed" },
    { label: "Entregado", value: "delivered" },
    { label: "Cancelado", value: "cancelled" },
];

const statusColors: Record<string, string> = {
    pending: "bg-amarillo/30 text-naranja",
    processing: "bg-azul/10 text-azul",
    completed: "bg-verde/10 text-green-700",
    delivered: "bg-green-100 text-green-700",
    cancelled: "bg-red-100 text-red-600",
};

const statusLabels: Record<string, string> = {
    pending: "Pendiente",
    processing: "Procesando",
    completed: "Completado",
    delivered: "Entregado",
    cancelled: "Cancelado",
};

async function changeStatus(order: Order) {
    try {
        await api.put(`/admin/orders/${order.id}/status`, {
            status: order.status,
        });
    } catch (e) {
        console.error("Error updating order status", e);
    }
}

async function deleteOrder(order: Order) {
    if (!confirm("¿Eliminar este pedido?")) return;
    try {
        await api.delete(`/admin/orders/${order.id}`);
        emit("deleted");
    } catch (e) {
        console.error("Error deleting order", e);
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
</script>

<template>
    <DataTable
        :value="orders"
        paginator
        :rows="10"
        stripedRows
        responsiveLayout="scroll"
        tableStyle="min-width: 1000px"
        class="bg-card border-borde rounded-2xl border"
    >
        <Column field="id" header="ID" :sortable="true" style="width: 80px">
            <template #body="{ data }">
                <span class="font-semibold">#{{ data.id }}</span>
            </template>
        </Column>

        <Column header="Cliente" :sortable="true" style="min-width: 140px">
            <template #body="{ data }">
                <div class="flex flex-col">
                    <span class="font-medium">{{ data.user?.name || data.shipping_name }}</span>
                    <span class="text-texto-secundario text-xs">{{ data.user?.email || "" }}</span>
                </div>
            </template>
        </Column>

        <Column header="Fecha" :sortable="true" style="width: 140px">
            <template #body="{ data }">
                <span class="text-texto-secundario text-sm">
                    {{ formatDate(data.created_at) }}
                </span>
            </template>
        </Column>

        <Column header="Productos" style="min-width: 180px">
            <template #body="{ data }">
                <div class="flex flex-col gap-0.5 text-sm">
                    <span v-for="item in data.items?.slice(0, 2)" :key="item.id">
                        {{ item.quantity }}x {{ item.article_name }}
                    </span>
                    <span v-if="data.items?.length > 2" class="text-texto-secundario text-xs">
                        +{{ data.items.length - 2 }} más
                    </span>
                </div>
            </template>
        </Column>

        <Column field="total" header="Total" :sortable="true" style="width: 100px">
            <template #body="{ data }">
                <span class="text-azul font-bold">
                    {{ Number(data.total).toFixed(2) }} €
                </span>
            </template>
        </Column>

        <Column header="Estado" style="width: 160px">
            <template #body="{ data }">
                <Select
                    v-model="data.status"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    class="w-full"
                    @update:modelValue="changeStatus(data)"
                />
            </template>
        </Column>

        <Column header="Acciones" style="width: 100px">
            <template #body="{ data }">
                <button
                    @click="deleteOrder(data)"
                    class="text-red-500 hover:text-red-700 transition p-1"
                    title="Eliminar pedido"
                >
                    <i class="pi pi-trash"></i>
                </button>
            </template>
        </Column>
    </DataTable>
</template>

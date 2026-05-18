<script setup lang="ts">
import { useCartStore } from "@/stores/cart";

const cart = useCartStore();

defineProps<{
    modelValue: boolean;
}>();

const emit = defineEmits(["update:modelValue"]);

function close() {
    emit("update:modelValue", false);
}
</script>

<template>
    <!-- BACKDROP -->
    <div
        v-if="modelValue"
        class="fixed inset-0 z-50 bg-black/40"
        @click="close"
    />

    <!-- DRAWER -->
    <aside
        class="bg-card fixed top-0 right-0 z-50 flex h-full w-full max-w-md flex-col shadow-2xl transition-transform md:w-105"
        :class="modelValue ? 'translate-x-0' : 'translate-x-full'"
    >
        <!-- HEADER -->
        <div class="flex shrink-0 items-center justify-between border-b p-5">
            <h2 class="text-rojo-fuerte text-xl font-bold">Tu carrito</h2>

            <button @click="close">
                <i class="pi pi-times"></i>
            </button>
        </div>

        <!-- ITEMS -->
        <div class="flex flex-1 flex-col gap-4 overflow-y-auto p-5">
            <div
                v-for="item in cart.items"
                :key="item.article.id"
                class="flex gap-3 rounded-2xl border p-3"
            >
                <img
                    :src="item.article.img"
                    class="h-16 w-16 rounded-xl object-cover"
                />

                <div class="flex flex-1 flex-col">
                    <p class="font-semibold">
                        {{ item.article.name }}
                    </p>

                    <p class="text-azul font-bold">{{ item.article.price }}€</p>

                    <!-- CONTROLS -->
                    <div class="mt-2 flex items-center gap-2">
                        <button @click="cart.decrease(item.article.id)">
                            -
                        </button>

                        <span>{{ item.quantity }}</span>

                        <button
                            @click="cart.increase(item.article.id)"
                            :disabled="
                                item.quantity >= (item.article.stock ?? 999)
                            "
                            class="disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            +
                        </button>

                        <!-- 🗑️ DELETE -->
                        <button
                            @click="cart.remove(item.article.id)"
                            class="ml-3 text-red-500 transition hover:text-red-700"
                            title="Eliminar producto"
                        >
                            <i class="pi pi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="bg-card shrink-0 border-t p-5">
            <div class="mb-3 flex justify-between">
                <span>Total</span>
                <span class="text-azul font-bold">
                    {{ cart.total.toFixed(2) }}€
                </span>
            </div>

            <RouterLink to="/checkout">
                <button
                    class="bg-azul w-full rounded-2xl py-3 text-white"
                    @click="close"
                >
                    Ir a pagar
                </button>
            </RouterLink>
        </div>
    </aside>
</template>

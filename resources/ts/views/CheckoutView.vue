<script setup lang="ts">
import { useCartStore } from "@/stores/cart";
import { ref, computed } from "vue";

const cart = useCartStore();
const method = ref("card");

function pay() {
    alert("Pago simulado realizado ✔");
    cart.clear();
}

const groupedItems = computed(() => cart.items);
</script>

<template>
    <div class="bg-fondo min-h-screen p-6 md:p-10">
        <div class="mx-auto max-w-5xl space-y-8">
            <!-- HEADER -->
            <div class="text-center md:text-left">
                <h1 class="text-rojo-fuerte text-4xl font-bold">Checkout</h1>
                <p class="text-texto-secundario mt-1">
                    Revisa tu pedido antes de finalizar la compra
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                <!-- LEFT: ITEMS -->
                <div class="space-y-6 md:col-span-2">
                    <div
                        class="bg-card border-borde rounded-3xl border p-6 shadow-sm"
                    >
                        <h2 class="text-rojo-fuerte mb-4 text-lg font-semibold">
                            Productos
                        </h2>

                        <div v-if="cart.items.length > 0" class="space-y-4">
                            <div
                                v-for="item in groupedItems"
                                :key="item.article.id"
                                class="border-borde flex items-center gap-4 rounded-2xl border bg-white p-4 transition hover:shadow-md"
                            >
                                <!-- IMAGE -->
                                <div
                                    class="border-borde bg-fondo h-20 w-20 shrink-0 overflow-hidden rounded-xl border"
                                >
                                    <!-- IMAGE -->
                                    <div
                                        class="border-borde bg-fondo h-20 w-20 shrink-0 overflow-hidden rounded-xl border"
                                    >
                                        <img
                                            :src="
                                                Array.isArray(
                                                    item.article.images,
                                                )
                                                    ? (item.article.images[0] ??
                                                      '/images/placeholder.jpg')
                                                    : (item.article.images ??
                                                      '/images/placeholder.jpg')
                                            "
                                            :alt="item.article.name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                </div>

                                <!-- INFO -->
                                <div
                                    class="flex flex-1 flex-col justify-between gap-1"
                                >
                                    <div>
                                        <p class="text-texto font-semibold">
                                            {{ item.article.name }}
                                        </p>

                                        <p
                                            class="text-texto-secundario text-sm"
                                        >
                                            Cantidad: {{ item.quantity }}
                                        </p>
                                    </div>

                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-texto-secundario text-xs"
                                        >
                                            {{
                                                item.article.onSale
                                                    ? "Oferta aplicada"
                                                    : "Precio estándar"
                                            }}
                                        </span>

                                        <span
                                            class="text-azul text-lg font-bold"
                                        >
                                            {{
                                                (
                                                    item.article.price *
                                                    item.quantity
                                                ).toFixed(2)
                                            }}
                                            €
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-else
                            class="text-texto-secundario py-10 text-center"
                        >
                            Tu carrito está vacío
                        </div>
                    </div>

                    <!-- PAYMENT -->
                    <div
                        class="bg-card border-borde rounded-3xl border p-6 shadow-sm"
                    >
                        <h2 class="text-rojo-fuerte mb-4 text-lg font-semibold">
                            Método de pago
                        </h2>

                        <div class="space-y-3">
                            <label
                                class="border-borde hover:bg-fondo flex cursor-pointer items-center gap-3 rounded-xl border p-3"
                            >
                                <input
                                    type="radio"
                                    value="card"
                                    v-model="method"
                                />
                                <span class="font-medium">Tarjeta</span>
                            </label>

                            <label
                                class="border-borde hover:bg-fondo flex cursor-pointer items-center gap-3 rounded-xl border p-3"
                            >
                                <input
                                    type="radio"
                                    value="paypal"
                                    v-model="method"
                                />
                                <span class="font-medium">PayPal</span>
                            </label>

                            <label
                                class="border-borde hover:bg-fondo flex cursor-pointer items-center gap-3 rounded-xl border p-3"
                            >
                                <input
                                    type="radio"
                                    value="cash"
                                    v-model="method"
                                />
                                <span class="font-medium"
                                    >Contra reembolso</span
                                >
                            </label>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: SUMMARY -->
                <div class="space-y-6">
                    <div
                        class="bg-card border-borde sticky top-6 rounded-3xl border p-6 shadow-sm"
                    >
                        <h2 class="text-rojo-fuerte mb-4 text-lg font-semibold">
                            Resumen
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div
                                class="text-texto-secundario flex justify-between"
                            >
                                <span>Productos</span>
                                <span>{{ cart.items.length }}</span>
                            </div>

                            <div
                                class="text-texto-secundario flex justify-between"
                            >
                                <span>Envío</span>
                                <span class="text-naranja font-semibold"
                                    >Gratis</span
                                >
                            </div>

                            <div
                                class="border-borde flex justify-between border-t pt-3 text-lg font-bold"
                            >
                                <span>Total</span>
                                <span class="text-azul">
                                    {{ cart.total.toFixed(2) }} €
                                </span>
                            </div>
                        </div>

                        <button
                            class="bg-azul hover:bg-azul/90 mt-6 w-full rounded-2xl py-3 font-semibold text-white transition active:scale-[0.99]"
                            @click="pay"
                        >
                            Pagar ahora
                        </button>

                        <p
                            class="text-texto-secundario mt-3 text-center text-xs"
                        >
                            Pago simulado · no se realiza ninguna transacción
                            real
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

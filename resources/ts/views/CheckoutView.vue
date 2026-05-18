<script setup lang="ts">
import { useCartStore } from "@/stores/cart";
import { ref } from "vue";

const cart = useCartStore();

const method = ref("card");

function pay() {
    alert("Pago simulado realizado ✔");
    cart.clear();
}
</script>

<template>
    <div class="bg-fondo min-h-screen p-8">
        <div class="mx-auto max-w-4xl">
            <h1 class="text-rojo-fuerte mb-6 text-3xl font-bold">Checkout</h1>

            <!-- ITEMS -->
            <div class="bg-card mb-6 rounded-3xl border p-6">
                <div
                    v-for="item in cart.items"
                    :key="item.article.id"
                    class="flex justify-between py-2"
                >
                    <span> {{ item.article.name }} x{{ item.quantity }} </span>

                    <span class="text-azul font-bold">
                        {{ (item.article.price * item.quantity).toFixed(2) }}€
                    </span>
                </div>
            </div>

            <!-- PAYMENT METHODS -->
            <div class="bg-card mb-6 rounded-3xl border p-6">
                <h2 class="mb-4 font-semibold">Método de pago</h2>

                <div class="flex flex-col gap-3">
                    <label>
                        <input type="radio" value="card" v-model="method" />
                        Tarjeta
                    </label>

                    <label>
                        <input type="radio" value="paypal" v-model="method" />
                        PayPal
                    </label>

                    <label>
                        <input type="radio" value="cash" v-model="method" />
                        Contra reembolso
                    </label>
                </div>
            </div>

            <!-- TOTAL -->
            <div class="bg-card mb-6 rounded-3xl border p-6">
                <div class="flex justify-between text-xl font-bold">
                    <span>Total</span>
                    <span class="text-azul">
                        {{ cart.total.toFixed(2) }}€
                    </span>
                </div>
            </div>

            <!-- PAY -->
            <button
                class="bg-azul w-full rounded-2xl py-4 text-white"
                @click="pay"
            >
                Pagar ahora
            </button>
        </div>
    </div>
</template>

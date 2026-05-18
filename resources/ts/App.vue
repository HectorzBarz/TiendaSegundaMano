<script setup lang="ts">
import "../css/app.css";
import Toast from "@volt/Toast.vue";
import BaseFooter from "./components/BaseFooter.vue";
import BaseHeader from "./components/BaseHeader.vue";
import { useCartStore } from "./stores/cart";
import CartDrawer from "./components/CartDrawer.vue";

const cart = useCartStore();
</script>
<template>
    <div class="flex min-h-screen flex-col">
        <Toast group="top-right" position="top-right" />
        <Toast group="top-center" position="top-center" />
        <Toast group="top-left" position="top-left" />
        <Toast group="center" position="center" />
        <Toast group="bottom-right" position="bottom-right" />
        <Toast group="bottom-center" position="bottom-center" />
        <Toast group="bottom-left" position="bottom-left" />

        <BaseHeader
            class="fixed bottom-0 z-50 order-2 w-full md:static md:order-1"
        />

        <main class="order-1 flex-1 md:order-2">
            <router-view />
        </main>

        <BaseFooter class="order-3" />

        <CartDrawer v-model="cart.open" />
    </div>
</template>

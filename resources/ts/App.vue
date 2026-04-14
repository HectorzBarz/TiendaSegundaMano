<script setup lang="ts">
import "../css/app.css";
import Toast from "@volt/Toast.vue";
import BaseFooter from "./components/BaseFooter.vue";
import BaseHeader from "./components/BaseHeader.vue";
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

        <BaseHeader />

        <main class="flex-1">
            <router-view />
        </main>

        <BaseFooter />
    </div>
</template>

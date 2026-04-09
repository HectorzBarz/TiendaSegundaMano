<script setup lang="ts">
import "../css/app.css";
import BaseFooter from "./components/BaseFooter.vue";
import BaseHeader from "./components/BaseHeader.vue";
</script>
<template>
    <div class="flex min-h-screen flex-col">
        <BaseHeader />

        <main class="flex-1">
            <router-view />
        </main>

        <BaseFooter />
    </div>
</template>

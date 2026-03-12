<script setup lang="ts">
import "../css/app.css";
import BaseFooter from "./components/BaseFooter.vue";
import BaseHeader from "./components/BaseHeader.vue";
</script>
<template>
    <BaseHeader />
    <div>
        <router-view />
    </div>
    <BaseFooter />
</template>

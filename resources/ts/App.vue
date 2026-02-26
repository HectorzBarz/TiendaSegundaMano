<script setup lang="ts">
import "../css/app.css";
import BaseHeader from "./components/BaseHeader.vue";
</script>
<template>
    <BaseHeader />
    <div>
        <router-view />
    </div>
</template>

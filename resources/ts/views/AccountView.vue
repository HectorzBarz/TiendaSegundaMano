<script setup lang="ts">
import { useRouter } from "vue-router";

const router = useRouter();

router.push({ path: "login" });
</script>

<template>
    <div><p>Esto es la área personal del usuario</p></div>
</template>

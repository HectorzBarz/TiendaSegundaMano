<script setup lang="ts">
defineProps<{
    to: string;
}>();
</script>

<template>
    <RouterLink
        :to="to"
        class="group relative z-0 flex h-full w-full overflow-hidden transition duration-300 hover:z-20 hover:-translate-y-1 hover:shadow-xl"
    >
        <!-- SHINE -->
        <div class="shine pointer-events-none absolute inset-0"></div>

        <!-- SLOT para contenido -->
        <div class="relative h-full w-full">
            <slot />
        </div>
    </RouterLink>
</template>

<style scoped>
/* Shine effect reutilizable */
.shine {
    background: linear-gradient(
        120deg,
        transparent 0%,
        rgba(255, 255, 255, 0.35) 50%,
        transparent 100%
    );
    transform: translateX(-100%);
    opacity: 0;
}

.group:hover .shine {
    animation: shine 0.8s ease-out;
    will-change: transform, opacity;
}

@keyframes shine {
    0% {
        transform: translateX(-120%);
        opacity: 0;
    }
    20% {
        opacity: 1;
    }
    100% {
        transform: translateX(120%);
        opacity: 0;
    }
}
</style>

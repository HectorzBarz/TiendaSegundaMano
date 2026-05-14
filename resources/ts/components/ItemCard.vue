<script setup lang="ts">
const props = defineProps<{
    title: string;
    description: string;
    icon: string; // ejemplo: "pi-user"
}>();
</script>

<template>
    <div
        class="group block rounded-2xl bg-gray-50 p-6 shadow transition hover:shadow-lg"
    >
        <div class="flex flex-col items-center text-center">
            <div
                class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-black text-3xl text-white transition group-hover:bg-gray-800"
            >
                <i class="pi" :class="icon"></i>
            </div>

            <h2 class="text-xl font-semibold text-gray-800">
                {{ title }}
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                {{ description }}
            </p>
        </div>
    </div>
</template>

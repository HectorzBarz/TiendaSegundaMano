<template>
    <Textarea
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    />
</template>

<script setup lang="ts">
import Textarea, {
    type TextareaPassThroughOptions,
    type TextareaProps,
} from "primevue/textarea";
import { ref } from "vue";
import { ptViewMerge } from "./utils";

interface Props extends /* @vue-ignore */ TextareaProps {}
defineProps<Props>();

const theme = ref<TextareaPassThroughOptions>({
    root: `
            appearance-none rounded-md outline-hidden

            bg-[var(--color-card)]
            text-[var(--color-texto)]

            border border-[var(--color-borde)]

            enabled:hover:border-[var(--color-azul)]
            enabled:focus:border-[var(--color-azul)]

            placeholder:text-[var(--color-texto-secundario)]

            disabled:bg-[var(--color-borde)]
            disabled:text-[var(--color-texto-secundario)]

            p-invalid:border-[var(--color-rojo-claro)]

            transition-colors duration-200
            shadow-[0_1px_2px_0_rgba(18,18,23,0.05)]
            `,
});
</script>

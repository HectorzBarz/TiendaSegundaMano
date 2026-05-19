<template>
    <InputText
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    />
</template>

<script setup lang="ts">
import InputText, {
    type InputTextPassThroughOptions,
    type InputTextProps,
} from "primevue/inputtext";
import { ref } from "vue";
import { ptViewMerge } from "./utils";

interface Props extends /* @vue-ignore */ InputTextProps {}
defineProps<Props>();

const theme = ref<InputTextPassThroughOptions>({
    root: `
            appearance-none rounded-md outline-hidden

            bg-[var(--color-card)]
            text-[var(--color-texto)]
            
            px-3 py-2 p-fluid:w-full
            p-small:text-sm p-small:px-[0.625rem] p-small:py-[0.375rem]
            p-large:text-lg p-large:px-[0.875rem] p-large:py-[0.625rem]

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

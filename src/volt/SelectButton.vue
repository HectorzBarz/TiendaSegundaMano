<template>
    <SelectButton
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    >
        <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
            <slot :name="slotName" v-bind="slotProps ?? {}" />
        </template>
    </SelectButton>
</template>

<script setup lang="ts">
import SelectButton, {
    type SelectButtonPassThroughOptions,
    type SelectButtonProps,
} from "primevue/selectbutton";

import { ref } from "vue";
import { ptViewMerge } from "./utils";

interface Props extends /* @vue-ignore */ SelectButtonProps {}
defineProps<Props>();

const theme = ref<SelectButtonPassThroughOptions>({
    root: `
        inline-flex overflow-hidden rounded-2xl

        border border-[var(--color-borde)]
        bg-[var(--color-card)]

        shadow-[0_1px_2px_0_rgba(18,18,23,0.05)]

        p-invalid:border-[var(--color-rojo-claro)]

        transition-colors duration-200
    `,

    pcToggleButton: {
        root: `
            relative inline-flex grow cursor-pointer select-none
            items-center justify-center overflow-hidden

            border-r border-[var(--color-borde)]
            last:border-r-0

            bg-[var(--color-card)]
            text-[var(--color-texto-secundario)]

            enabled:hover:bg-[var(--color-fondo)]
            enabled:hover:text-[var(--color-texto)]

            disabled:cursor-not-allowed
            disabled:opacity-50

            transition-all duration-200

            p-invalid:border-[var(--color-rojo-claro)]
        `,

        content: `
            relative inline-flex items-center justify-center gap-2

            rounded-xl

            px-5 py-2.5

            text-sm font-medium

            transition-all duration-200

            p-checked:bg-[var(--color-azul)]
            p-checked:text-white
            p-checked:shadow-sm
        `,

        icon: `
            text-sm
        `,

        label: `
            whitespace-nowrap
        `,
    },
});
</script>

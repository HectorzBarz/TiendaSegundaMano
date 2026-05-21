<template>
    <ToggleSwitch
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    >
        <template v-for="(_, slotName) in $slots" v-slot:[slotName]="slotProps">
            <slot :name="slotName" v-bind="slotProps ?? {}" />
        </template>
    </ToggleSwitch>
</template>

<script setup lang="ts">
import ToggleSwitch, {
    type ToggleSwitchPassThroughOptions,
    type ToggleSwitchProps,
} from "primevue/toggleswitch";
import { ref } from "vue";
import { ptViewMerge } from "./utils";

interface Props extends /* @vue-ignore */ ToggleSwitchProps {}
defineProps<Props>();

const theme = ref<ToggleSwitchPassThroughOptions>({
    root: `inline-block w-10 h-6`,

    input: `
        peer cursor-pointer disabled:cursor-default
        appearance-none absolute top-0 start-0
        w-full h-full m-0 p-0 opacity-0 z-10
        rounded-[30px]
    `,

    slider: `
        inline-block w-full h-full rounded-[30px]
        shadow-[0_1px_2px_0_rgba(18,18,23,0.05)]
        bg-[var(--color-borde)] 
        border border-transparent
        transition-colors duration-200

        /* hover */
        peer-enabled:peer-hover:bg-[var(--color-azul)]/60

        /* ACTIVO (ON) */
        p-checked:bg-[var(--color-azul)]
        peer-enabled:peer-hover:p-checked:bg-[var(--color-azul)]

        /* invalid / disabled */
        p-invalid:border-[var(--color-rojo-claro)]
        p-disabled:bg-surface-200
    `,

    handle: `
        absolute top-1/2 flex justify-center items-center
        bg-surface-0 text-surface-500
        w-4 h-4 start-1 -mt-2 rounded-full
        transition-[background,color,left] duration-200

        /* ACTIVO */
        p-checked:start-5
        p-checked:text-[var(--color-azul)]
    `,
});
</script>

<template>
    <Checkbox
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    >
        <template #icon="{ checked, indeterminate, dataP }">
            <CheckIcon v-if="checked" :class="theme.icon" :data-p="dataP" />
            <MinusIcon
                v-else-if="indeterminate"
                :class="theme.icon"
                :data-p="dataP"
            />
        </template>
    </Checkbox>
</template>

<script setup lang="ts">
import CheckIcon from "@primevue/icons/check";
import MinusIcon from "@primevue/icons/minus";
import Checkbox, {
    type CheckboxPassThroughOptions,
    type CheckboxProps,
} from "primevue/checkbox";
import { ref } from "vue";
import { ptViewMerge } from "./utils";

interface Props extends /* @vue-ignore */ CheckboxProps {}
defineProps<Props>();

const theme = ref<CheckboxPassThroughOptions>({
    root: `relative inline-flex select-none w-5 h-5 align-bottom
        p-small:w-4 p-small:h-4
        p-large:w-6 p-large:h-6`,
    input: `peer cursor-pointer disabled:cursor-default appearance-none
        absolute start-0 top-0 w-full h-full m-0 p-0 opacity-0 z-10
        border border-transparent rounded-xs`,
    box: `
        flex justify-center items-center rounded-sm w-5 h-5

        border border-[var(--color-borde)]
        bg-[var(--color-card)]
        text-[var(--color-texto)]

        peer-hover:border-[var(--color-azul)]

        p-checked:bg-[var(--color-azul)]
        p-checked:border-[var(--color-azul)]
        p-checked:text-white

        p-invalid:border-[var(--color-rojo-claro)]

        transition-colors duration-200
        `,
    icon: `text-sm w-[0.875rem] h-[0.875rem] transition-none
        p-small:w-3 p-small:h-3
        p-large:w-4 p-large:h-4`,
});
</script>

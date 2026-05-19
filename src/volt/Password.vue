<template>
    <Password
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    >
        <template #maskicon="{ toggleCallback }">
            <EyeSlashIcon
                @click="toggleCallback"
                class="text-surface-500 absolute end-3 top-1/2 -mt-2 h-4 w-4"
            />
        </template>
        <template #unmaskicon="{ toggleCallback }">
            <EyeIcon
                @click="toggleCallback"
                class="text-surface-500 absolute end-3 top-1/2 -mt-2 h-4 w-4"
            />
        </template>
        <template v-for="(_, slotName) in $slots" v-slot:[slotName]="slotProps">
            <slot :name="slotName" v-bind="slotProps ?? {}" />
        </template>
    </Password>
</template>

<script setup lang="ts">
import EyeIcon from "@primevue/icons/eye";
import EyeSlashIcon from "@primevue/icons/eyeslash";
import Password, {
    type PasswordPassThroughOptions,
    type PasswordProps,
} from "primevue/password";
import { ref } from "vue";
import { ptViewMerge } from "./utils";

interface Props extends /* @vue-ignore */ PasswordProps {}
defineProps<Props>();

const theme = ref<PasswordPassThroughOptions>({
    root: `inline-flex relative p-fluid:flex`,
    pcInputText: {
        root: `
        appearance-none rounded-md outline-hidden

        bg-[var(--color-card)]
        text-[var(--color-texto)]

        px-3 py-2 p-fluid:w-full p-has-e-icon:pe-10

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
    },
    overlay: `p-3 rounded-md p-portal-self:min-w-full
        bg-surface-0 bg-surface-900
        border border-surface-200 border-surface-700
        text-surface-700 text-surface-0
        shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)]`,
    content: `flex flex-col gap-2`,
    meter: `h-3 bg-surface-200 bg-surface-700 rounded-md`,
    meterLabel: `
            h-full rounded-md transition-all

            p-weak:bg-[var(--color-rojo-claro)]
            p-medium:bg-[var(--color-amarillo)]
            p-strong:bg-[var(--color-azul)]
            `,
    meterText: ``,
    transition: {
        enterFromClass: "opacity-0 scale-y-75",
        enterActiveClass:
            "transition duration-120 ease-[cubic-bezier(0,0,0.2,1)]",
        leaveActiveClass: "transition-opacity duration-100 ease-linear",
        leaveToClass: "opacity-0",
    },
});
</script>

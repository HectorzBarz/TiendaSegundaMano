<script setup lang="ts">
import AutoComplete from "@volt/AutoComplete.vue";
import InputNumber from "@volt/InputNumber.vue";
import { ref } from "vue";

const props = defineProps<{
    suggestions: any[];
}>();

const value = ref(null);
const filtered = ref<any[]>([]);

const search = (event: any) => {
    if (!event.query) {
        filtered.value = props.suggestions;
        return;
    }

    filtered.value = props.suggestions.filter((article) =>
        article.name.toLowerCase().includes(event.query.toLowerCase()),
    );
};

const price = ref(0);
</script>

<template>
    <div class="flex min-w-1/6 flex-col gap-2 bg-red-200">
        <!-- Name -->
        <div>
            <AutoComplete
                v-model="value"
                inputId="name"
                :suggestions="filtered"
                optionLabel="name"
                @complete="search"
                placeholder="Nombre"
                class="w-full bg-white"
            />
        </div>

        <!-- Price -->
        <div>
            <InputNumber
                v-model="price"
                inputId="price"
                :min="0"
                :max="100"
                fluid
                class="w-full bg-white"
            />
        </div>
    </div>
</template>

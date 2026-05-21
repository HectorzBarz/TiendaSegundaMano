<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore, api } from "@/stores/auth";
import { useAppToast } from "@/composables/useAppToast";

import Button from "@volt/Button.vue";
import InputText from "@volt/InputText.vue";
// Asegúrate de que este tipo exista o defínelo aquí
import { Category } from "@/types";

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();
const { show } = useAppToast();

if (!auth.user?.is_admin) {
    router.push("/account");
}

// 1. Usamos 'null' como valor inicial y definimos el tipo explícitamente
const category = ref<Category>();
const isLoading = ref(true);

const imageFile = ref<File | null>(null);
const preview = ref("");

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        imageFile.value = target.files[0];
        preview.value = URL.createObjectURL(target.files[0]);
    }
}

async function saveCategory() {
    // 2. Guardia de seguridad: si no hay categoría, no hacemos nada
    if (!category.value) return;

    try {
        const formData = new FormData();
        formData.append("name", category.value.name);

        if (imageFile.value) {
            formData.append("image", imageFile.value);
        }

        // Accedemos de forma segura a category.value.id
        await api.post(`/categories/${category.value.id}`, formData);

        show({
            severity: "success",
            message: "Categoría actualizada correctamente",
            life: 3000,
        });

        router.push("/admin");
    } catch (e) {
        show({
            severity: "error",
            message: "Error al actualizar la categoría",
            life: 3000,
        });
    }
}

const deleteCategory = async (category: Category) => {
    if (confirm(`¿Eliminar la categoría ${category.name}?`)) {
        try {
            await api.delete(`/categories/${category.id}`);

            router.push("/admin");
        } catch (e) {
            console.error(e);
            alert("Error al eliminar la categoría");
        }
    }
};

onMounted(async () => {
    try {
        // 3. Tipado de la respuesta
        const response = await api.get<Category>(
            `/categories/${route.params.id}`,
        );
        category.value = response.data;
        preview.value = category.value.image; // Asignamos la preview inicial
    } catch (e) {
        console.error(e);
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <div v-if="isLoading" class="p-6">Cargando categoría...</div>
    <div v-else-if="category" class="bg-fondo min-h-screen p-6">
        <div class="mx-auto max-w-7xl">
            <!-- HEADER -->
            <div
                class="bg-card border-borde mb-8 flex flex-col gap-6 rounded-3xl border p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between"
            >
                <div class="flex items-center gap-5">
                    <div
                        class="bg-azul/10 flex h-20 w-20 items-center justify-center rounded-3xl"
                    >
                        <i class="pi pi-tags text-azul text-4xl"></i>
                    </div>
                    <div>
                        <h1
                            class="text-rojo-fuerte text-3xl font-bold tracking-tight"
                        >
                            Editar categoría
                        </h1>
                        <p class="text-texto-secundario mt-2">
                            ID #{{ category!.id }} - Gestiona la información de
                            esta categoría.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <Button
                        label="Volver"
                        icon="pi pi-arrow-left"
                        outlined
                        class="rounded-2xl"
                        @click="router.back()"
                    />
                    <Button
                        label="Guardar cambios"
                        icon="pi pi-save"
                        class="bg-azul! hover:bg-azul/90! rounded-2xl border-0 px-6 text-white"
                        @click="saveCategory"
                    />
                </div>
            </div>

            <!-- GRID -->
            <div class="grid gap-8 xl:grid-cols-[1fr_380px]">
                <!-- MAIN FORM -->
                <div
                    class="bg-card border-borde overflow-hidden rounded-3xl border shadow-sm"
                >
                    <div class="border-borde border-b px-8 py-6">
                        <h2 class="text-rojo-fuerte text-2xl font-semibold">
                            Detalles
                        </h2>
                    </div>

                    <div class="grid gap-8 p-8">
                        <div class="flex flex-col gap-2">
                            <label
                                class="text-rojo-fuerte text-sm font-semibold"
                                >Nombre de la categoría</label
                            >
                            <InputText
                                v-model="category!.name"
                                class="rounded-2xl"
                            />
                        </div>

                        <div class="flex flex-col gap-4">
                            <label
                                class="text-rojo-fuerte text-sm font-semibold"
                                >Imagen representativa</label
                            >
                            <div class="grid gap-6 lg:grid-cols-[200px_1fr]">
                                <div
                                    class="border-borde h-40 overflow-hidden rounded-3xl border bg-white"
                                >
                                    <img
                                        :src="preview"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <label
                                    class="border-borde bg-amarillo/10 hover:border-azul flex h-40 cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed transition"
                                >
                                    <i
                                        class="pi pi-images text-azul mb-2 text-2xl"
                                    ></i>
                                    <p
                                        class="text-rojo-fuerte text-sm font-semibold"
                                    >
                                        Cambiar imagen
                                    </p>
                                    <input
                                        type="file"
                                        accept=".jpg,.jpeg,.png,.webp,.avif,.svg"
                                        @change="handleFileChange"
                                    />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="flex flex-col gap-6">
                    <div class="border-borde rounded-3xl border bg-red-50 p-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100"
                            >
                                <i class="pi pi-trash text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-red-700">
                                    Zona peligrosa
                                </h3>
                                <p class="mt-1 text-sm text-red-600">
                                    Eliminar esta categoría no afectará a los
                                    productos asignados, pero eliminará la
                                    referencia visual.
                                </p>
                                <Button
                                    label="Eliminar categoría"
                                    icon="pi pi-trash"
                                    class="mt-5 rounded-2xl border-0 bg-red-500! text-white hover:bg-red-600!"
                                    @click="deleteCategory(category)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

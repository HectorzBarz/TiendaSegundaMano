<script setup lang="ts">
import { ref } from "vue";

import { useAuthStore } from "@/stores/auth";

import Button from "@volt/Button.vue";
import InputText from "@volt/InputText.vue";

import { Category } from "@/types";
import { RouterLink, useRouter } from "vue-router";

// Instancias del Store y Router
const auth = useAuthStore();
const router = useRouter();

// Redirección si el usuario no está autenticado
if (!auth.user?.is_admin) {
    router.push("account");
}

const category = ref<Category>({
    id: 0,
    name: "",
    img: "",
});

const preview = "https://placehold.co/600x400/f8f7f5/70191d?text=Vista+previa";
</script>

<template>
    <div class="bg-fondo min-h-screen p-6">
        <!-- CONTAINER -->
        <div class="mx-auto max-w-5xl">
            <!-- HEADER -->
            <div
                class="bg-card border-borde mb-8 flex flex-col gap-6 rounded-3xl border p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between"
            >
                <!-- LEFT -->
                <div class="flex items-center gap-5">
                    <div
                        class="bg-azul/10 flex h-24 w-24 items-center justify-center rounded-3xl"
                    >
                        <i class="pi pi-tags text-azul text-5xl"></i>
                    </div>

                    <div>
                        <h1
                            class="text-rojo-fuerte text-3xl font-bold tracking-tight"
                        >
                            Crear categoría
                        </h1>

                        <p class="text-texto-secundario mt-2">
                            Añade una nueva categoría para organizar tu
                            catálogo.
                        </p>
                    </div>
                </div>

                <!-- STATUS -->
                <div
                    class="bg-amarillo/20 border-borde flex items-center gap-4 rounded-2xl border px-5 py-4"
                >
                    <div
                        class="bg-naranja/20 flex h-12 w-12 items-center justify-center rounded-2xl"
                    >
                        <i class="pi pi-folder text-naranja text-2xl"></i>
                    </div>

                    <div>
                        <p class="text-rojo-fuerte font-semibold">
                            Nueva categoría
                        </p>

                        <p class="text-texto-secundario text-sm">
                            Formulario de creación
                        </p>
                    </div>
                </div>
            </div>

            <!-- PANEL -->
            <div
                class="bg-card border-borde overflow-hidden rounded-3xl border shadow-sm"
            >
                <!-- TOP -->
                <div
                    class="border-borde flex flex-col gap-4 border-b px-8 py-6 lg:flex-row lg:items-center lg:justify-between"
                >
                    <div>
                        <h2 class="text-rojo-fuerte text-2xl font-semibold">
                            Información de la categoría
                        </h2>

                        <p class="text-texto-secundario mt-1 text-sm">
                            Configura el nombre y la imagen representativa.
                        </p>
                    </div>

                    <div
                        class="bg-azul/10 text-azul flex items-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold"
                    >
                        <i class="pi pi-check-circle"></i>

                        Borrador
                    </div>
                </div>

                <!-- FORM -->
                <div class="flex flex-col gap-10 p-8">
                    <!-- NAME -->
                    <div class="flex flex-col gap-3">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Nombre de la categoría
                        </label>

                        <InputText
                            v-model="category.name"
                            placeholder="Ej: Tecnología"
                            class="rounded-2xl"
                        />

                        <p class="text-texto-secundario text-sm">
                            Este nombre será visible para los usuarios.
                        </p>
                    </div>

                    <!-- IMAGE -->
                    <div class="flex flex-col gap-4">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Imagen de la categoría
                        </label>

                        <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
                            <!-- PREVIEW -->
                            <div
                                class="border-borde overflow-hidden rounded-3xl border bg-white"
                            >
                                <img
                                    :src="preview"
                                    alt="Preview"
                                    class="h-full w-full object-cover"
                                />
                            </div>

                            <!-- DROPZONE -->
                            <div
                                class="border-borde bg-amarillo/10 hover:border-azul flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed px-6 py-10 transition"
                            >
                                <div
                                    class="bg-azul/10 text-azul mb-4 flex h-16 w-16 items-center justify-center rounded-2xl text-2xl"
                                >
                                    <i class="pi pi-images"></i>
                                </div>

                                <p
                                    class="text-rojo-fuerte text-center font-semibold"
                                >
                                    Arrastra una imagen aquí
                                </p>

                                <p
                                    class="text-texto-secundario mt-1 text-center text-sm"
                                >
                                    o pulsa para seleccionar un archivo
                                </p>

                                <p
                                    class="text-texto-secundario mt-4 text-center text-xs"
                                >
                                    Recomendado: imagen cuadrada y fondo limpio.
                                </p>

                                <input
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div
                    class="border-borde flex flex-col gap-4 border-t bg-slate-50 px-8 py-6 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p class="text-rojo-fuerte font-semibold">
                            Revisión final
                        </p>

                        <p class="text-texto-secundario text-sm">
                            Comprueba los datos antes de guardar la categoría.
                        </p>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Button
                            label="Cancelar"
                            icon="pi pi-times"
                            severity="secondary"
                            outlined
                            class="bg-naranja! hover:bg-naranja/90! rounded-2xl px-6 py-3 text-white!"
                        />

                        <RouterLink to="/admin">
                            <Button
                                label="Guardar categoría"
                                icon="pi pi-save"
                                class="bg-azul! hover:bg-azul/90! rounded-2xl border-0 px-6 py-3 text-white transition"
                            />
                        </RouterLink>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

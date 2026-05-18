<script setup lang="ts">
import { ref } from "vue";

import Button from "@volt/Button.vue";
import InputText from "@volt/InputText.vue";
import InputNumber from "@volt/InputNumber.vue";
import Textarea from "@volt/Textarea.vue";
import Select from "@volt/Select.vue";
import Checkbox from "@volt/Checkbox.vue";

import { Article } from "@/types";

const categories = [
    { label: "Accesorios", value: 1 },
    { label: "Tecnología", value: 2 },
    { label: "Hogar", value: 3 },
    { label: "Gaming", value: 4 },
    { label: "Ropa", value: 5 },
];

const states = [
    { label: "Nuevo", value: 1 },
    { label: "Seminuevo", value: 2 },
    { label: "Usado", value: 3 },
];

const article = ref<Article>({
    id: 0,
    name: "",
    description: "",
    oldPrice: 0,
    price: 0,
    onSale: false,
    img: "",
    itemState: 1,
    categoryId: 1,
    stock: 0,
});

const preview = "https://placehold.co/600x400/f8f7f5/70191d?text=Vista+previa";
</script>

<template>
    <div class="bg-fondo min-h-screen p-6">
        <!-- CONTAINER -->
        <div class="mx-auto max-w-6xl">
            <!-- HEADER -->
            <div
                class="bg-card border-borde mb-8 flex flex-col gap-6 rounded-3xl border p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between"
            >
                <!-- LEFT -->
                <div class="flex items-center gap-5">
                    <div
                        class="bg-azul/10 flex h-24 w-24 items-center justify-center rounded-3xl"
                    >
                        <i class="pi pi-box text-azul text-5xl"></i>
                    </div>

                    <div>
                        <h1
                            class="text-rojo-fuerte text-3xl font-bold tracking-tight"
                        >
                            Añadir artículo
                        </h1>

                        <p class="text-texto-secundario mt-2">
                            Crea un nuevo producto para tu catálogo.
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
                        <i class="pi pi-shopping-bag text-naranja text-2xl"></i>
                    </div>

                    <div>
                        <p class="text-rojo-fuerte font-semibold">
                            Nuevo producto
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
                            Información del artículo
                        </h2>

                        <p class="text-texto-secundario mt-1 text-sm">
                            Completa todos los datos del producto.
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
                <div class="grid gap-8 p-8 lg:grid-cols-2">
                    <!-- NAME -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Nombre del artículo
                        </label>

                        <InputText
                            v-model="article.name"
                            placeholder="Ej: Teclado mecánico"
                            class="rounded-2xl"
                        />
                    </div>

                    <!-- CATEGORY -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Categoría
                        </label>

                        <Select
                            v-model="article.categoryId"
                            :options="categories"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Selecciona categoría"
                            class="rounded-2xl"
                        />
                    </div>

                    <!-- PRICE -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Precio actual
                        </label>

                        <InputNumber
                            v-model="article.price"
                            mode="currency"
                            currency="EUR"
                            locale="es-ES"
                            fluid
                            inputClass="rounded-2xl"
                        />
                    </div>

                    <!-- OLD PRICE -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Precio anterior

                            <span class="text-texto-secundario font-normal">
                                (opcional)
                            </span>
                        </label>

                        <InputNumber
                            v-model="article.oldPrice"
                            mode="currency"
                            currency="EUR"
                            locale="es-ES"
                            fluid
                            inputClass="rounded-2xl"
                        />
                    </div>

                    <!-- STOCK -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Stock disponible
                        </label>

                        <InputNumber
                            v-model="article.stock"
                            :min="0"
                            fluid
                            inputClass="rounded-2xl"
                        />
                    </div>

                    <!-- STATE -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Estado del producto
                        </label>

                        <Select
                            v-model="article.itemState"
                            :options="states"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Selecciona estado"
                            class="rounded-2xl"
                        />
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="flex flex-col gap-2 lg:col-span-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Descripción
                        </label>

                        <Textarea
                            v-model="article.description"
                            rows="6"
                            autoResize
                            placeholder="Describe el producto..."
                            class="rounded-2xl"
                        />
                    </div>

                    <!-- OFFER -->
                    <div
                        class="bg-amarillo/10 border-borde flex items-center gap-4 rounded-3xl border p-5 lg:col-span-2"
                    >
                        <Checkbox
                            v-model="article.onSale"
                            binary
                            inputId="sale"
                        />

                        <div>
                            <label
                                for="sale"
                                class="text-rojo-fuerte cursor-pointer font-semibold"
                            >
                                Producto en oferta
                            </label>

                            <p class="text-texto-secundario text-sm">
                                Marca esta opción si el producto tiene
                                descuento.
                            </p>
                        </div>
                    </div>

                    <!-- IMAGE -->
                    <div class="flex flex-col gap-4 lg:col-span-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Imagen del producto
                        </label>

                        <div class="grid gap-6 lg:grid-cols-[300px_1fr]">
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
                                    <i class="pi pi-image"></i>
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
                            Comprueba la información antes de guardar.
                        </p>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Button
                            label="Cancelar"
                            icon="pi pi-times"
                            severity="secondary"
                            outlined
                            class="rounded-2xl px-6 py-3"
                        />

                        <Button
                            label="Guardar artículo"
                            icon="pi pi-save"
                            class="bg-azul! hover:bg-azul/90! rounded-2xl border-0 px-6 py-3 text-white transition"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

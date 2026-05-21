<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

import Button from "@volt/Button.vue";
import InputText from "@volt/InputText.vue";
import InputNumber from "@volt/InputNumber.vue";
import Textarea from "@volt/Textarea.vue";
import Select from "@volt/Select.vue";
import Checkbox from "@volt/Checkbox.vue";

import { api, useAuthStore } from "@/stores/auth";
import { Article, Category } from "@/types";

// Instancias del Store y Router
const auth = useAuthStore();
const router = useRouter();

// Redirección si el usuario no está autenticado o no es administrador
if (!auth.user?.is_admin) {
    router.push({ name: "account" }); // Ajusta según el esquema de tus rutas
}

const categories = ref<Category[]>([]);

const loadCategories = async () => {
    try {
        const response = await api.get<Category[]>("/categories");

        categories.value = response.data.map((category) => ({
            label: category.name,
            value: category.id,
        }));

        // Seleccionar la primera categoría automáticamente
        if (categories.value.length > 0) {
            article.value.categoryId = categories.value[0].value;
        }
    } catch (error) {
        console.error("Error cargando categorías:", error);
    }
};

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

// Manejo de Imágenes
const fileInput = ref<HTMLInputElement | null>(null);
const selectedFiles = ref<File[]>([]);
const previews = ref<string[]>([]);

const triggerFileUpload = () => fileInput.value?.click();

const onFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        const files = Array.from(target.files);

        // Limitar a un máximo estricto de 5 imágenes totales
        const remainingSlots = 5 - selectedFiles.value.length;
        const filesToAdd = files.slice(0, remainingSlots);

        filesToAdd.forEach((file) => {
            selectedFiles.value.push(file);
            previews.value.push(URL.createObjectURL(file));
        });
    }
    // Limpiar el valor para permitir subir el mismo archivo si fue eliminado previamente
    if (target) target.value = "";
};

const removeImage = (index: number) => {
    // Revocar la URL de memoria para prevenir memory leaks
    URL.revokeObjectURL(previews.value[index]);

    selectedFiles.value.splice(index, 1);
    previews.value.splice(index, 1);
};

// Envío al Backend
const isLoading = ref(false);
// Errores de validación reactivos para inyectar en la interfaz si lo deseas en el futuro
const validationErrors = ref<Record<string, string[]>>({});

const saveArticle = async () => {
    try {
        isLoading.value = true;
        validationErrors.value = {};

        // Estructuración de datos Binarios y de Texto nativos
        const formData = new FormData();
        formData.append("name", article.value.name || "");
        formData.append("description", article.value.description || "");
        formData.append("price", String(article.value.price));
        formData.append("old_price", String(article.value.oldPrice));
        formData.append("on_sale", article.value.onSale ? "1" : "0");
        formData.append("category_id", String(article.value.categoryId));
        formData.append("item_state", String(article.value.itemState));
        formData.append("stock", String(article.value.stock));

        // Añadir el listado de imágenes mapeado como array para Laravel
        selectedFiles.value.forEach((file) => {
            formData.append("images[]", file);
        });

        // Petición HTTP usando la API interceptada del Store
        await api.post("articles", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        router.push("/admin");
    } catch (error) {
        console.error("Error al guardar:", error);

        // Corrección de tipos estricta para AxiosError en TypeScript
        if (axios.isAxiosError(error) && error.response) {
            if (error.response.status === 422) {
                validationErrors.value = error.response.data.errors;
                console.table(error.response.data.errors);
            }
        }
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    loadCategories();
});
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
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Nombre del artículo</label
                        >
                        <InputText
                            v-model="article.name"
                            placeholder="Ej: Teclado mecánico"
                            class="rounded-2xl"
                        />
                    </div>

                    <!-- CATEGORY -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Categoría</label
                        >
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
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Precio actual</label
                        >
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
                            <span class="text-texto-secundario font-normal"
                                >(opcional)</span
                            >
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
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Stock disponible</label
                        >
                        <InputNumber
                            v-model="article.stock"
                            :min="0"
                            fluid
                            inputClass="rounded-2xl"
                        />
                    </div>

                    <!-- STATE -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Estado del producto</label
                        >
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
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Descripción</label
                        >
                        <Textarea
                            v-model="article.description"
                            rows="6"
                            autoResize
                            placeholder="Describe el producto..."
                            class="rounded-2xl p-2"
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

                    <!-- IMAGES (MULTI-UPLOAD) -->
                    <div class="flex flex-col gap-4 lg:col-span-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Imágenes del producto (Máximo 5)
                        </label>

                        <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
                            <!-- DROPZONE -->
                            <div
                                @click="triggerFileUpload"
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
                                    Pulsa para añadir imágenes
                                </p>
                                <p
                                    class="text-texto-secundario mt-1 text-center text-sm"
                                >
                                    Sube hasta
                                    {{ 5 - selectedFiles.length }} imágenes más
                                </p>

                                <input
                                    ref="fileInput"
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    class="hidden"
                                    @change="onFileSelect"
                                />
                            </div>

                            <!-- PREVIEWS GRID -->
                            <div class="grid grid-cols-3 gap-2">
                                <div
                                    v-for="(url, index) in previews"
                                    :key="url"
                                    class="border-borde group relative aspect-square overflow-hidden rounded-xl border bg-white shadow-sm"
                                >
                                    <img
                                        :src="url"
                                        class="h-full w-full object-cover"
                                        alt="Vista previa"
                                    />
                                    <button
                                        type="button"
                                        @click.stop="removeImage(index)"
                                        class="absolute top-1 right-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100 hover:bg-red-600"
                                        title="Eliminar imagen"
                                    >
                                        <i class="pi pi-times"></i>
                                    </button>
                                </div>

                                <!-- Placeholder si no hay nada -->
                                <div
                                    v-if="previews.length === 0"
                                    class="border-borde col-span-3 flex aspect-3/1 items-center justify-center rounded-xl border border-dashed bg-slate-50 opacity-50"
                                >
                                    <div class="text-center text-slate-400">
                                        <i
                                            class="pi pi-camera mb-1 text-2xl"
                                        ></i>
                                        <p class="text-xs">Sin imágenes</p>
                                    </div>
                                </div>
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
                            class="bg-naranja! hover:bg-naranja/90! rounded-2xl px-6 py-3 text-white!"
                            @click="router.back()"
                        />

                        <Button
                            label="Guardar artículo"
                            icon="pi pi-save"
                            :loading="isLoading"
                            @click="saveArticle"
                            class="bg-azul! hover:bg-azul/90! rounded-2xl border-0 px-6 py-3 text-white transition"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

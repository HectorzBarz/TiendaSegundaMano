<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { api } from "@/stores/auth";

import Button from "@volt/Button.vue";
import InputText from "@volt/InputText.vue";
import InputNumber from "@volt/InputNumber.vue";
import Textarea from "@volt/Textarea.vue";
import ToggleSwitch from "@volt/ToggleSwitch.vue";
import Select from "@volt/Select.vue";

import { useAuthStore } from "@/stores/auth";
import { useAppToast } from "@/composables/useAppToast";

import type { Article, Category } from "@/types";

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();
const { show } = useAppToast();

if (!auth.user?.is_admin) {
    router.push("/account");
}

/**
 * STATE
 */
const article = ref<Article | null>(null);
const categories = ref<{ label: string; value: number }[]>([]);
const loading = ref(true);

/**
 * IMAGES
 * - images = todo lo visible (backend + previews)
 * - newImages = SOLO archivos nuevos
 */
const images = ref<string[]>([]);
const newImages = ref<File[]>([]);

const mainImage = computed(() => images.value[0] ?? "");

/**
 * LOAD CATEGORIES
 */
const loadCategories = async () => {
    try {
        const { data } = await api.get<Category[]>("/categories");

        categories.value = data.map((c) => ({
            label: c.name,
            value: c.id,
        }));
    } catch (err) {
        console.error("Error cargando categorías", err);
    }
};

/**
 * FETCH ARTICLE
 */
const fetchArticle = async () => {
    try {
        const id = route.params.id;

        const { data } = await api.get(`/articles/${id}`);

        let rawImages: string[] = [];

        if (Array.isArray(data.images)) {
            rawImages = data.images;
        } else if (typeof data.images === "string") {
            try {
                rawImages = JSON.parse(data.images);
            } catch {
                rawImages = [data.images];
            }
        }

        const baseUrl =
            api.defaults.baseURL?.replace(/\/api\/?$/, "") ||
            "http://localhost:8000";

        images.value = rawImages.map((img: string) =>
            img.startsWith("http") ? img : `${baseUrl}/storage/${img}`,
        );

        article.value = {
            ...data,
            onSale: Boolean(data.on_sale),
            oldPrice: data.old_price,
            categoryId: data.category_id,
        };
    } catch (err) {
        console.error("Error cargando artículo:", err);
        article.value = null;
    } finally {
        loading.value = false;
    }
};

/**
 * HANDLE IMAGES
 */
const handleImages = (event: Event) => {
    const files = (event.target as HTMLInputElement).files;
    if (!files) return;

    const remaining = 5 - images.value.length;

    Array.from(files)
        .slice(0, remaining)
        .forEach((file) => {
            newImages.value.push(file);
            images.value.push(URL.createObjectURL(file));
        });
};

/**
 * REMOVE IMAGE (CORREGIDO)
 */
const removeImage = (index: number) => {
    const removed = images.value[index];

    images.value.splice(index, 1);

    /**
     * Si era una imagen NUEVA (blob), la quitamos de newImages
     */
    if (removed.startsWith("blob:")) {
        const fileIndex = newImages.value.findIndex(
            (_, i) => i === index, // sincronizado por orden
        );

        if (fileIndex !== -1) {
            newImages.value.splice(fileIndex, 1);
        }
    }
};

/**
 * SAVE ARTICLE
 */
const saveArticle = async () => {
    if (!article.value) return;

    /**
     * VALIDACIONES FRONTEND
     */
    if (!article.value.name?.trim()) {
        show({
            severity: "warn",
            message: "El nombre del artículo es obligatorio",
            life: 3000,
            position: "top-right",
        });
        return;
    }

    if (article.value.price == null || article.value.price <= 0) {
        show({
            severity: "warn",
            message: "El precio debe ser mayor a 0",
            life: 3000,
            position: "top-right",
        });
        return;
    }

    if (!article.value.categoryId) {
        show({
            severity: "warn",
            message: "Debes seleccionar una categoría",
            life: 3000,
            position: "top-right",
        });
        return;
    }

    if (article.value.stock == null || article.value.stock < 0) {
        show({
            severity: "warn",
            message: "El stock no puede ser negativo",
            life: 3000,
            position: "top-right",
        });
        return;
    }

    if (images.value.length === 0) {
        show({
            severity: "warn",
            message: "Debes añadir al menos una imagen",
            life: 3000,
            position: "top-right",
        });
        return;
    }

    /**
     * ENVÍO
     */
    const id = route.params.id;

    const formData = new FormData();

    formData.append("name", article.value.name);
    formData.append("description", article.value.description ?? "");
    formData.append("price", String(article.value.price));
    formData.append("old_price", String(article.value.oldPrice ?? 0));
    formData.append("on_sale", article.value.onSale ? "1" : "0");
    formData.append("category_id", String(article.value.categoryId));
    formData.append("stock", String(article.value.stock ?? 0));

    /**
     * EXISTENTES
     */
    images.value.forEach((img) => {
        if (!img.startsWith("blob:")) {
            formData.append(
                "existing_images[]",
                img.replace(/^.*\/storage\//, ""),
            );
        }
    });

    /**
     * NUEVAS
     */
    newImages.value.forEach((file) => {
        formData.append("new_images[]", file);
    });

    try {
        await api.post(`/articles/${id}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        show({
            severity: "success",
            message: "Artículo actualizado correctamente",
            life: 3000,
            position: "top-right",
        });

        newImages.value = [];
        await fetchArticle();

        router.push("/admin");
    } catch (err) {
        console.error(err);

        show({
            severity: "error",
            message: "Error al actualizar el artículo",
            life: 3000,
            position: "top-right",
        });
    }
};

onMounted(() => {
    fetchArticle();
    loadCategories();
});
</script>

<template>
    <div v-if="article" class="bg-fondo min-h-screen p-6">
        <!-- CONTAINER -->
        <div class="mx-auto max-w-7xl">
            <!-- HEADER -->
            <div
                class="bg-card border-borde mb-8 flex flex-col gap-6 rounded-3xl border p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between"
            >
                <!-- LEFT -->
                <div class="flex items-center gap-5">
                    <!-- PRODUCT IMAGE -->
                    <div class="relative">
                        <img
                            :src="mainImage"
                            :alt="article.name"
                            class="border-borde h-28 w-28 rounded-3xl border-4 object-cover shadow-sm"
                        />

                        <!-- SALE -->
                        <div
                            v-if="article.onSale"
                            class="absolute -top-2 -right-2 rounded-full bg-red-500 px-3 py-1 text-xs font-bold text-white shadow"
                        >
                            Oferta
                        </div>
                    </div>

                    <!-- INFO -->
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1
                                class="text-rojo-fuerte text-3xl font-bold tracking-tight"
                            >
                                Editar artículo
                            </h1>

                            <span
                                class="rounded-full bg-blue-100 px-4 py-2 text-xs font-semibold text-blue-700"
                            >
                                ID #{{ article.id }}
                            </span>
                        </div>

                        <p class="text-texto-secundario mt-2">
                            Actualiza la información, imágenes y stock del
                            producto.
                        </p>
                    </div>
                </div>

                <!-- ACTIONS -->
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
                        @click="saveArticle"
                    />
                </div>
            </div>

            <!-- GRID -->
            <div class="grid gap-8 xl:grid-cols-[1fr_380px]">
                <!-- MAIN -->
                <div
                    class="bg-card border-borde overflow-hidden rounded-3xl border shadow-sm"
                >
                    <!-- TOP -->
                    <div class="border-borde border-b px-8 py-6">
                        <h2 class="text-rojo-fuerte text-2xl font-semibold">
                            Información del producto
                        </h2>

                        <p class="text-texto-secundario mt-1 text-sm">
                            Modifica únicamente los campos necesarios.
                        </p>
                    </div>

                    <!-- FORM -->
                    <div class="grid gap-8 p-8">
                        <!-- NAME -->
                        <div class="flex flex-col gap-2">
                            <label
                                class="text-rojo-fuerte text-sm font-semibold"
                            >
                                Nombre del producto
                            </label>

                            <InputText
                                v-model="article.name"
                                class="border-borde focus:border-azul hover:border-azul rounded-2xl px-4 py-3 transition"
                            />
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="flex flex-col gap-2">
                            <label
                                class="text-rojo-fuerte text-sm font-semibold"
                            >
                                Descripción
                            </label>

                            <Textarea
                                v-model="article.description"
                                rows="6"
                                autoResize
                                class="border-borde focus:border-azul hover:border-azul rounded-2xl px-4 py-3 transition"
                            />
                        </div>

                        <!-- PRICE GRID -->
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- PRICE -->
                            <div class="flex flex-col gap-2">
                                <label
                                    class="text-rojo-fuerte text-sm font-semibold"
                                >
                                    Precio actual
                                </label>

                                <InputNumber
                                    v-model="article.price"
                                    mode="currency"
                                    currency="EUR"
                                    locale="es-ES"
                                    fluid
                                    inputClass="w-full rounded-2xl border-borde px-4 py-3"
                                />
                            </div>

                            <!-- OLD PRICE -->
                            <div class="flex flex-col gap-2">
                                <label
                                    class="text-rojo-fuerte text-sm font-semibold"
                                >
                                    Precio anterior
                                </label>

                                <InputNumber
                                    v-model="article.oldPrice"
                                    mode="currency"
                                    currency="EUR"
                                    locale="es-ES"
                                    fluid
                                    inputClass="w-full rounded-2xl border-borde px-4 py-3"
                                />
                            </div>
                        </div>

                        <!-- CATEGORY + STOCK -->
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- CATEGORY -->
                            <div class="flex flex-col gap-2">
                                <label
                                    class="text-rojo-fuerte text-sm font-semibold"
                                >
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

                            <!-- STOCK -->
                            <div class="flex flex-col gap-2">
                                <label
                                    class="text-rojo-fuerte text-sm font-semibold"
                                >
                                    Stock
                                </label>

                                <InputNumber
                                    v-model="article.stock"
                                    fluid
                                    inputClass="w-full rounded-2xl border-borde px-4 py-3"
                                />
                            </div>
                        </div>

                        <!-- SALE -->
                        <div
                            class="bg-amarillo/10 border-borde flex items-center justify-between rounded-3xl border p-5"
                        >
                            <div>
                                <h3 class="text-rojo-fuerte font-semibold">
                                    Producto en oferta
                                </h3>

                                <p class="text-texto-secundario mt-1 text-sm">
                                    Activa esta opción para mostrar descuentos.
                                </p>
                            </div>

                            <ToggleSwitch v-model="article.onSale" />
                        </div>

                        <!-- IMAGES -->
                        <div class="flex flex-col gap-4">
                            <div>
                                <label
                                    class="text-rojo-fuerte text-sm font-semibold"
                                >
                                    Imágenes del producto
                                </label>

                                <p class="text-texto-secundario mt-1 text-sm">
                                    Puedes subir hasta 5 imágenes.
                                </p>
                            </div>

                            <!-- GRID -->
                            <div
                                class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-5"
                            >
                                <!-- EXISTING -->
                                <div
                                    v-for="(image, index) in images"
                                    :key="index"
                                    class="group relative overflow-hidden rounded-3xl"
                                >
                                    <img
                                        :src="image"
                                        class="h-40 w-full object-cover"
                                    />

                                    <!-- DELETE -->
                                    <button
                                        class="absolute top-3 right-3 flex h-10 w-10 items-center justify-center rounded-2xl bg-red-500 text-white opacity-0 shadow transition group-hover:opacity-100"
                                        @click="removeImage(index)"
                                    >
                                        <i class="pi pi-trash"></i>
                                    </button>

                                    <!-- COVER -->
                                    <div
                                        v-if="index === 0"
                                        class="absolute bottom-3 left-3 rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-700 shadow"
                                    >
                                        Portada
                                    </div>
                                </div>

                                <!-- UPLOAD -->
                                <label
                                    v-if="images.length < 5"
                                    class="border-borde bg-amarillo/10 hover:border-azul flex h-40 cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed transition"
                                >
                                    <div
                                        class="bg-azul/10 text-azul mb-3 flex h-14 w-14 items-center justify-center rounded-2xl text-2xl"
                                    >
                                        <i class="pi pi-image"></i>
                                    </div>

                                    <p
                                        class="text-rojo-fuerte text-sm font-semibold"
                                    >
                                        Añadir imagen
                                    </p>

                                    <input
                                        type="file"
                                        accept="image/*"
                                        multiple
                                        class="hidden"
                                        @change="handleImages"
                                    />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="flex flex-col gap-6">
                    <!-- STATUS -->
                    <div
                        class="bg-card border-borde rounded-3xl border p-6 shadow-sm"
                    >
                        <div class="mb-5 flex items-center gap-3">
                            <div
                                class="bg-azul/10 flex h-12 w-12 items-center justify-center rounded-2xl"
                            >
                                <i
                                    class="pi pi-chart-line text-azul text-xl"
                                ></i>
                            </div>

                            <div>
                                <h2
                                    class="text-rojo-fuerte text-xl font-semibold"
                                >
                                    Estadísticas
                                </h2>

                                <p class="text-texto-secundario text-sm">
                                    Rendimiento del producto
                                </p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- SALES -->
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-texto-secundario text-sm">
                                    Vendidos
                                </p>

                                <h3
                                    class="text-rojo-fuerte mt-2 text-3xl font-bold"
                                >
                                    {{ article.sell_count ?? 0 }}
                                </h3>
                            </div>

                            <!-- STOCK -->
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-texto-secundario text-sm">
                                    Stock actual
                                </p>

                                <h3
                                    :class="
                                        (article.stock ?? 0) <= 5
                                            ? 'text-red-600'
                                            : 'text-azul'
                                    "
                                    class="mt-2 text-3xl font-bold"
                                >
                                    {{ article.stock ?? 0 }}
                                </h3>
                            </div>

                            <!-- STATUS -->
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-texto-secundario text-sm">
                                    Estado
                                </p>

                                <div class="mt-3">
                                    <span
                                        v-if="(article.stock ?? 0) === 0"
                                        class="rounded-full bg-red-100 px-4 py-2 text-xs font-bold text-red-700"
                                    >
                                        Sin stock
                                    </span>

                                    <span
                                        v-else-if="(article.stock ?? 0) < 10"
                                        class="rounded-full bg-amber-100 px-4 py-2 text-xs font-bold text-amber-700"
                                    >
                                        Stock bajo
                                    </span>

                                    <span
                                        v-else
                                        class="rounded-full bg-blue-100 px-4 py-2 text-xs font-bold text-blue-700"
                                    >
                                        Disponible
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DANGER ZONE -->
                    <div class="border-borde rounded-3xl border bg-red-50 p-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100"
                            >
                                <i
                                    class="pi pi-exclamation-triangle text-red-600"
                                ></i>
                            </div>

                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-red-700">
                                    Zona peligrosa
                                </h3>

                                <p class="mt-1 text-sm text-red-600">
                                    Eliminar este artículo borrará toda su
                                    información permanentemente.
                                </p>

                                <Button
                                    label="Eliminar artículo"
                                    icon="pi pi-trash"
                                    class="mt-5 rounded-2xl border-0 bg-red-500! text-white hover:bg-red-600!"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="flex h-screen items-center justify-center">
        <p class="text-xl">Cargando artículo...</p>
    </div>
</template>

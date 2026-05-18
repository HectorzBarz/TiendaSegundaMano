<script setup lang="ts">
import ItemCard from "@/components/ItemCard.vue";
import { ref } from "vue";
import { RouterLink } from "vue-router";

const showAdmin = ref(true);
</script>

<template>
    <main
        class="bg-fondo flex min-h-screen items-center justify-center px-4 py-10"
    >
        <section
            class="bg-card border-borde w-full max-w-5xl rounded-3xl border p-10 shadow-2xl"
        >
            <!-- HEADER -->
            <h1 class="text-rojo-fuerte mb-10 text-center text-4xl font-bold">
                Mi Cuenta
            </h1>

            <!-- GRID -->
            <div
                class="grid gap-6"
                style="
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                "
            >
                <!-- MIS DATOS -->
                <RouterLink to="/account/profile">
                    <ItemCard
                        title="Mis datos"
                        description="Revisa y actualiza tu información personal."
                        icon="pi-user"
                        class="h-full w-full"
                    />
                </RouterLink>

                <!-- COMPRAS -->
                <RouterLink to="/account/history">
                    <ItemCard
                        title="Mis compras"
                        description="Consulta tu historial de pedidos."
                        icon="pi-shopping-cart"
                        class="h-full w-full"
                    />
                </RouterLink>

                <!-- WISHLIST -->
                <RouterLink to="/account/wishlist">
                    <ItemCard
                        title="Lista de deseados"
                        description="Consulta tu lista de productos guardados."
                        icon="pi-star-fill"
                        class="h-full w-full"
                    />
                </RouterLink>

                <!-- ADMIN -->
                <RouterLink v-if="showAdmin" to="/admin">
                    <ItemCard
                        title="Administrador"
                        description="Gestiona productos y el panel de control."
                        icon="pi-crown"
                        class="h-full w-full"
                    />
                </RouterLink>

                <!-- CREATE PRODUCT -->
                <RouterLink v-if="showAdmin" to="/admin/create">
                    <ItemCard
                        title="Añadir artículo"
                        description="Crear un nuevo producto para el catálogo."
                        icon="pi-plus"
                        class="h-full w-full"
                    />
                </RouterLink>
            </div>
        </section>
    </main>
</template>

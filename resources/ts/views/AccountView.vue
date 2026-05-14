<script setup lang="ts">
import ItemCard from "@/components/ItemCard.vue";
import { ref } from "vue";
import { RouterLink, useRouter } from "vue-router";

// Ejemplo: en el futuro puedes controlar esto con roles
const showAdmin = ref(true);

const router = useRouter();
// router.push({ path: "login" });
</script>

<template>
    <main
        class="flex min-h-screen items-center justify-center bg-gray-100 px-4 py-10"
    >
        <section class="w-full max-w-5xl rounded-3xl bg-white p-10 shadow-2xl">
            <h1 class="mb-10 text-center text-4xl font-bold text-gray-800">
                Mi Cuenta
            </h1>

            <!-- GRID FLEXIBLE -->
            <div
                class="grid gap-6"
                style="
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                "
            >
                <!-- CARD: Datos -->
                <RouterLink to="/account/profile">
                    <ItemCard
                        title="Mis datos"
                        description="Revisa y actualiza tu información personal."
                        icon="pi-user"
                        class="h-full w-full"
                    />
                </RouterLink>

                <!-- CARD: Compras -->
                <RouterLink to="/account/orders">
                    <ItemCard
                        title=" Mis compras"
                        description="Consulta tu historial de pedidos."
                        icon="pi-shopping-cart"
                        class="h-full w-full"
                    />
                </RouterLink>

                <!-- CARD: Lista de deseados -->
                <RouterLink to="/account/wishlist">
                    <ItemCard
                        title="Lista de deseados"
                        description="Consulta tu lista de deseados."
                        icon="pi-star-fill"
                        class="h-full w-full"
                    />
                </RouterLink>

                <!-- CARD: Panel Admin -->
                <RouterLink v-if="showAdmin" to="/admin">
                    <ItemCard
                        title="Administrador"
                        description="Gestiona productos y el panel de control."
                        icon="pi-crown"
                        class="h-full w-full"
                    />
                </RouterLink>
            </div>
        </section>
    </main>
</template>

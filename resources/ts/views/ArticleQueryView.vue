<script setup lang="ts">
import { useAuthStore } from "@/stores/auth";
import { computed, onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const auth = useAuthStore(); // Instanciar el store
const articleId = route.params.id;

// Sesión real basada en el store
const isLoggedIn = computed(() => !!auth.user);

// form
const name = ref("");
const email = ref("");
const message = ref("");

// Precarga de datos al montar si el usuario está logueado
onMounted(() => {
    if (isLoggedIn.value && auth.user) {
        name.value = auth.user.name;
        email.value = auth.user.email;
    }
});

// usamos el id como referencia directa
const productRef = computed(() => `Producto #${articleId}`);

// estado UI
const loading = ref(false);

// validación
const isValid = computed(() => {
    if (!message.value) return false;
    if (message.value.length > 150) return false;

    if (!isLoggedIn.value) {
        if (!name.value || !email.value) return false;
    }

    return true;
});

const submit = async () => {
    if (!isValid.value) return;

    loading.value = true;

    await new Promise((r) => setTimeout(r, 1500));

    loading.value = false;

    alert("Consulta enviada correctamente");
};
</script>
<template>
    <main class="bg-fondo flex min-h-screen justify-center p-6">
        <div class="w-full max-w-3xl">
            <!-- HEADER (igual estilo dashboard) -->
            <div
                class="bg-card border-borde mb-6 rounded-3xl border p-8 shadow-sm"
            >
                <h1 class="text-rojo-fuerte text-3xl font-bold tracking-tight">
                    Consulta de producto
                </h1>

                <p class="text-texto-secundario mt-2">
                    Rellena el formulario para enviar tu consulta al vendedor.
                </p>
            </div>

            <!-- FORM CARD -->
            <div
                class="bg-card border-borde space-y-6 rounded-3xl border p-8 shadow-sm"
            >
                <!-- PRODUCTO INFO (tipo badge dashboard) -->
                <div
                    class="bg-fondo border-borde flex items-center justify-between rounded-2xl border p-4"
                >
                    <div>
                        <p class="text-texto-secundario text-sm">
                            Producto seleccionado
                        </p>
                        <p class="text-rojo-fuerte font-semibold">
                            {{ productRef }}
                        </p>
                    </div>

                    <i class="pi pi-box text-azul text-2xl"></i>
                </div>

                <!-- NOMBRE -->
                <div>
                    <label class="text-texto text-sm font-medium">
                        Nombre y apellidos
                    </label>

                    <input
                        v-model="name"
                        :readonly="isLoggedIn"
                        :class="[
                            'border-borde mt-2 w-full rounded-2xl border px-4 py-3 focus:ring-2 focus:outline-none',
                            isLoggedIn
                                ? 'cursor-not-allowed bg-slate-100 text-gray-500'
                                : 'bg-fondo focus:ring-azul/20',
                        ]"
                        type="text"
                        placeholder="Tu nombre completo"
                    />
                </div>

                <div>
                    <label class="text-texto text-sm font-medium">
                        Email
                    </label>

                    <input
                        v-model="email"
                        :readonly="isLoggedIn"
                        :class="[
                            'border-borde mt-2 w-full rounded-2xl border px-4 py-3 focus:ring-2 focus:outline-none',
                            isLoggedIn
                                ? 'cursor-not-allowed bg-slate-100 text-gray-500'
                                : 'bg-fondo focus:ring-azul/20',
                        ]"
                        type="email"
                        placeholder="tu@email.com"
                    />
                </div>

                <!-- CONSULTA -->
                <div>
                    <label class="text-texto text-sm font-medium">
                        Consulta
                    </label>

                    <textarea
                        v-model="message"
                        maxlength="150"
                        rows="5"
                        class="border-borde bg-fondo focus:ring-azul/20 mt-2 w-full rounded-2xl border px-4 py-3 focus:ring-2 focus:outline-none"
                        placeholder="Escribe tu consulta..."
                    />

                    <div class="mt-1 flex justify-end">
                        <p class="text-texto-secundario text-xs">
                            {{ message.length }}/150
                        </p>
                    </div>
                </div>

                <!-- BOTÓN (siempre visible, estilo Volt/admin) -->
                <button
                    @click="submit"
                    :disabled="!isValid || loading"
                    class="bg-azul hover:bg-azul/90 disabled:bg-azul/40 flex w-full items-center justify-center rounded-2xl py-3 font-semibold text-white transition"
                >
                    <span v-if="!loading"> Enviar consulta </span>

                    <span v-else class="flex items-center gap-2">
                        <i class="pi pi-spin pi-spinner"></i>
                        Enviando...
                    </span>
                </button>
            </div>
        </div>
    </main>
</template>

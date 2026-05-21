<script setup lang="ts">
import { ref } from "vue";
import InputText from "@volt/InputText.vue";
import { RouterLink, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth"; // Importamos la store

const auth = useAuthStore();
const router = useRouter();

// ----------------------
// STATE
// ----------------------
const form = ref({
    email: "",
    password: "",
});

const errors = ref<Record<string, string>>({});
const submitted = ref(false);

// ----------------------
// REGEX
// ----------------------
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// ----------------------
// VALIDATION
// ----------------------
function validate() {
    errors.value = {};

    if (!form.value.email) {
        errors.value.email = "Email obligatorio";
    } else if (!emailRegex.test(form.value.email)) {
        errors.value.email = "Email inválido";
    }

    if (!form.value.password) {
        errors.value.password = "Contraseña obligatoria";
    }

    return Object.keys(errors.value).length === 0;
}

// ----------------------
// FIELD CLASS
// ----------------------
function fieldClass(field: string) {
    if (!submitted.value) {
        return "focus:border-azul focus:ring-azul/20";
    }

    if (errors.value[field]) {
        return "border-red-500 focus:border-red-500 focus:ring-red-200";
    }

    return "border-green-500 focus:border-green-500 focus:ring-green-200";
}

// ----------------------
// SUBMIT ACTUALIZADO
// ----------------------
async function submit() {
    submitted.value = true;
    if (!validate()) return;

    try {
        await auth.login({
            email: form.value.email,
            password: form.value.password,
        });

        console.log("Login OK");
        router.push("/"); // Redirigir al inicio o al área personal
    } catch (error: any) {
        console.error("Error en login:", error);
        // Si Laravel devuelve error 422 de validación (credenciales incorrectas)
        if (error.response?.status === 422) {
            errors.value.email = "Credenciales incorrectas";
        } else {
            errors.value.email = "Error de conexión con el servidor";
        }
    }
}
</script>

<template>
    <main class="bg-fondo flex min-h-screen items-center justify-center px-4">
        <section
            class="bg-card border-borde w-full max-w-4xl overflow-hidden rounded-3xl border shadow-2xl"
        >
            <div class="grid md:grid-cols-2">
                <!-- FORM -->
                <div class="flex flex-col justify-center p-10">
                    <h1
                        class="text-rojo-fuerte mb-8 text-center text-4xl font-bold"
                    >
                        Inicio de Sesión
                    </h1>

                    <div class="space-y-5">
                        <!-- EMAIL -->
                        <InputText
                            v-model="form.email"
                            placeholder="Correo"
                            class="w-full rounded-xl border p-3 transition focus:ring-2"
                            :class="fieldClass('email')"
                        />

                        <p v-if="errors.email" class="text-sm text-red-500">
                            {{ errors.email }}
                        </p>

                        <!-- PASSWORD -->
                        <InputText
                            v-model="form.password"
                            type="password"
                            placeholder="Contraseña"
                            class="w-full rounded-xl border p-3 transition focus:ring-2"
                            :class="fieldClass('password')"
                        />

                        <p v-if="errors.password" class="text-sm text-red-500">
                            {{ errors.password }}
                        </p>

                        <!-- BUTTON -->
                        <button
                            @click="submit"
                            class="bg-azul hover:bg-azul/90 w-full rounded-xl py-3 font-semibold text-white transition hover:cursor-pointer"
                        >
                            Entrar
                        </button>

                        <!-- REGISTER LINK -->
                        <div class="text-center">
                            <span class="text-texto-secundario">
                                ¿No tienes cuenta?
                            </span>

                            <RouterLink
                                to="/register"
                                class="text-rojo-fuerte ml-2 font-semibold hover:underline"
                            >
                                Regístrate
                            </RouterLink>
                        </div>
                    </div>
                </div>

                <!-- IMAGE -->
                <div
                    class="bg-amarillo/10 flex items-center justify-center p-10"
                >
                    <img
                        src="/public/img/favicon.svg"
                        alt="logo"
                        class="h-52 md:h-72"
                    />
                </div>
            </div>
        </section>
    </main>
</template>

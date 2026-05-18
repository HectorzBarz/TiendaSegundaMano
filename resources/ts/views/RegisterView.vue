<script setup lang="ts">
import { ref, computed } from "vue";
import InputText from "@volt/InputText.vue";
import { RouterLink } from "vue-router";

// ----------------------
// FORM STATE
// ----------------------
const form = ref({
    fullName: "",
    birthDate: "",
    phone: "",
    email: "",
    password: "",
    confirmPassword: "",
    shippingAddress: "",
    billingAddress: "",
    favoriteCategory: "",
    referralCode: "",
});

// ----------------------
// REGEX
// ----------------------

// 1–2 nombres + 1–2 apellidos, solo letras
const nameRegex = /^([A-Za-zÁÉÍÓÚáéíóúÑñ]+)(\s[A-Za-zÁÉÍÓÚáéíóúÑñ]+){1,3}$/;

// Fecha DD/MM/YYYY
const birthRegex =
    /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19\d{2}|20\d{2})$/;

// Teléfono internacional (+34...)
const phoneRegex = /^\+\d{1,4}\s?\d{6,14}$/;

// Email
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Dirección básica (mínimo número + calle)
const addressRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,º\-]{5,}$/;

// Password strength
const passwordStrongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

// ----------------------
// VALIDATION HELPERS
// ----------------------
const errors = ref<Record<string, string>>({});

function validate() {
    errors.value = {};

    // NAME
    if (!nameRegex.test(form.value.fullName)) {
        errors.value.fullName =
            "Introduce 1–2 nombres y 1–2 apellidos sin números ni símbolos";
    }

    // BIRTH DATE
    if (!birthRegex.test(form.value.birthDate)) {
        errors.value.birthDate = "Formato DD/MM/YYYY inválido";
    } else {
        const [d, m, y] = form.value.birthDate.split("/").map(Number);
        const birth = new Date(y, m - 1, d);

        const age = new Date().getFullYear() - birth.getFullYear();

        if (age < 18 || age > 100) {
            errors.value.birthDate = "Debes tener entre 18 y 100 años";
        }
    }

    // PHONE
    if (!phoneRegex.test(form.value.phone)) {
        errors.value.phone = "Teléfono internacional inválido (+34...)";
    }

    // EMAIL
    if (!emailRegex.test(form.value.email)) {
        errors.value.email = "Email inválido";
    }

    // ADDRESS
    if (!addressRegex.test(form.value.shippingAddress)) {
        errors.value.shippingAddress = "Dirección inválida";
    }

    // PASSWORD
    if (!passwordStrongRegex.test(form.value.password)) {
        errors.value.password =
            "La contraseña debe ser media o fuerte (mayús, minús, número y símbolo)";
    }

    if (form.value.password !== form.value.confirmPassword) {
        errors.value.confirmPassword = "Las contraseñas no coinciden";
    }

    return Object.keys(errors.value).length === 0;
}

// ----------------------
// PASSWORD LEVEL
// ----------------------
const passwordStrength = computed(() => {
    const p = form.value.password;

    let score = 0;
    if (p.length >= 8) score++;
    if (/[a-z]/.test(p)) score++;
    if (/[A-Z]/.test(p)) score++;
    if (/\d/.test(p)) score++;
    if (/[\W_]/.test(p)) score++;

    if (score <= 2) return "Débil";
    if (score === 3) return "Media";
    return "Fuerte";
});

// ----------------------
// FORMAT NAME (capitalize)
// ----------------------
function formatName() {
    form.value.fullName = form.value.fullName
        .split(" ")
        .map((w) => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
        .join(" ");
}

// ----------------------
// SUBMIT
// ----------------------
function submit() {
    if (!validate()) return;

    console.log("Formulario válido:", form.value);
}
</script>

<template>
    <main
        class="bg-fondo flex min-h-screen items-center justify-center px-4 py-10"
    >
        <section
            class="bg-card border-borde w-full max-w-5xl overflow-hidden rounded-3xl border shadow-2xl"
        >
            <div class="grid md:grid-cols-2">
                <!-- FORM -->
                <div class="space-y-5 p-10">
                    <h1
                        class="text-rojo-fuerte mb-6 text-center text-4xl font-bold"
                    >
                        Registro
                    </h1>

                    <!-- NAME -->
                    <div>
                        <InputText
                            v-model="form.fullName"
                            placeholder="Nombre completo"
                            class="focus:border-azul focus:ring-azul/20 w-full rounded-xl border p-3 transition focus:ring-2"
                            @blur="formatName"
                        />
                        <p
                            v-if="errors.fullName"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.fullName }}
                        </p>
                    </div>

                    <!-- BIRTH -->
                    <div>
                        <InputText
                            v-model="form.birthDate"
                            placeholder="DD/MM/YYYY"
                            class="focus:border-azul focus:ring-azul/20 w-full rounded-xl border p-3 transition focus:ring-2"
                        />
                        <p
                            v-if="errors.birthDate"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.birthDate }}
                        </p>
                    </div>

                    <!-- PHONE -->
                    <div>
                        <InputText
                            v-model="form.phone"
                            placeholder="+34 600000000"
                            class="focus:border-azul focus:ring-azul/20 w-full rounded-xl border p-3 transition focus:ring-2"
                        />
                        <p
                            v-if="errors.phone"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.phone }}
                        </p>
                    </div>

                    <!-- EMAIL -->
                    <InputText
                        v-model="form.email"
                        placeholder="Correo"
                        class="focus:border-azul focus:ring-azul/20 w-full rounded-xl border p-3 focus:ring-2"
                    />

                    <!-- ADDRESS -->
                    <InputText
                        v-model="form.shippingAddress"
                        placeholder="Dirección de envío"
                        class="focus:border-azul focus:ring-azul/20 w-full rounded-xl border p-3 focus:ring-2"
                    />

                    <!-- BILLING COPY -->
                    <label
                        class="text-texto-secundario flex items-center gap-2 text-sm"
                    >
                        <input type="checkbox" v-model="form.billingAddress" />
                        Usar misma dirección de envío
                    </label>

                    <!-- PASSWORD -->
                    <InputText
                        v-model="form.password"
                        type="password"
                        placeholder="Contraseña"
                        class="focus:border-azul focus:ring-azul/20 w-full rounded-xl border p-3 focus:ring-2"
                    />

                    <!-- METER -->
                    <meter
                        class="w-full"
                        min="0"
                        max="3"
                        :value="
                            passwordStrength === 'Débil'
                                ? 1
                                : passwordStrength === 'Media'
                                  ? 2
                                  : 3
                        "
                    ></meter>

                    <p class="text-texto-secundario text-sm">
                        Seguridad: {{ passwordStrength }}
                    </p>

                    <!-- CONFIRM -->
                    <InputText
                        v-model="form.confirmPassword"
                        type="password"
                        placeholder="Confirmar contraseña"
                        class="focus:border-azul focus:ring-azul/20 w-full rounded-xl border p-3 focus:ring-2"
                    />

                    <!-- SUBMIT -->
                    <button
                        @click="submit"
                        class="bg-azul hover:bg-azul/90 w-full rounded-xl py-3 font-semibold text-white transition"
                    >
                        Registrarse
                    </button>

                    <div class="text-center text-sm">
                        <span class="text-texto-secundario"
                            >¿Ya tienes cuenta?</span
                        >
                        <RouterLink
                            to="/login"
                            class="text-azul ml-2 font-semibold hover:underline"
                        >
                            Inicia sesión
                        </RouterLink>
                    </div>
                </div>

                <!-- IMAGE -->
                <div
                    class="bg-amarillo/10 hidden items-center justify-center p-10 md:flex"
                >
                    <img src="/public/img/favicon.svg" class="h-64" />
                </div>
            </div>
        </section>
    </main>
</template>

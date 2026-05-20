<script setup lang="ts">
import { ref, computed, watch } from "vue";
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
    useShippingAsBilling: false,

    city: "",
    postalCode: "",

    favoriteCategory: "",
    referralCode: "",
});

// ----------------------
// REGEX
// ----------------------

// 1–2 nombres + 1–2 apellidos
const nameRegex = /^([A-Za-zÁÉÍÓÚáéíóúÑñ]+)(\s[A-Za-zÁÉÍÓÚáéíóúÑñ]+){1,3}$/;

// Fecha DD/MM/YYYY
const birthRegex =
    /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19\d{2}|20\d{2})$/;

// Teléfono internacional
const phoneRegex = /^\+\d{1,4}\s?\d{6,14}$/;

// Email
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Dirección
const addressRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,ºª\-]{5,}$/;

// Ciudad
const cityRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-]{2,}$/;

// Código postal español
const postalRegex = /^[0-9]{5}$/;

// Password fuerte
const passwordStrongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

// ----------------------
// AUTO COPY ADDRESS
// ----------------------
watch(
    () => form.value.useShippingAsBilling,
    (checked) => {
        if (checked) {
            form.value.billingAddress = form.value.shippingAddress;
        } else {
            form.value.billingAddress = "";
        }
    },
);

watch(
    () => form.value.shippingAddress,
    (value) => {
        if (form.value.useShippingAsBilling) {
            form.value.billingAddress = value;
        }
    },
);

// ----------------------
// VALIDATION
// ----------------------
const errors = ref<Record<string, string>>({});

function validate() {
    errors.value = {};

    // NAME
    if (!nameRegex.test(form.value.fullName)) {
        errors.value.fullName = "Introduce nombres y apellidos válidos";
    }

    // BIRTH DATE
    if (!birthRegex.test(form.value.birthDate)) {
        errors.value.birthDate = "Formato DD/MM/YYYY inválido";
    }

    // PHONE
    if (!phoneRegex.test(form.value.phone)) {
        errors.value.phone = "Teléfono internacional inválido";
    }

    // EMAIL
    if (!emailRegex.test(form.value.email)) {
        errors.value.email = "Email inválido";
    }

    // SHIPPING ADDRESS
    if (!addressRegex.test(form.value.shippingAddress)) {
        errors.value.shippingAddress = "Dirección de envío inválida";
    }

    // BILLING ADDRESS
    if (
        !form.value.useShippingAsBilling &&
        !addressRegex.test(form.value.billingAddress)
    ) {
        errors.value.billingAddress = "Dirección de facturación inválida";
    }

    // CITY
    if (!cityRegex.test(form.value.city)) {
        errors.value.city = "Introduce una ciudad válida";
    }

    // POSTAL CODE
    if (!postalRegex.test(form.value.postalCode)) {
        errors.value.postalCode = "Código postal inválido";
    }

    // PASSWORD
    if (passwordStrength.value === "Débil") {
        errors.value.password =
            "La contraseña debe tener seguridad media o fuerte";
    }

    // CONFIRM PASSWORD
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
// FORMAT NAME
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
                            class="w-full"
                            v-model="form.fullName"
                            placeholder="Nombre completo"
                            @blur="
                                formatName();
                                validate();
                            "
                        />

                        <p
                            v-if="errors.fullName"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.fullName }}
                        </p>
                    </div>

                    <!-- CITY -->
                    <div>
                        <InputText
                            class="w-full"
                            v-model="form.city"
                            placeholder="Ciudad"
                            @blur="validate"
                        />

                        <p v-if="errors.city" class="mt-1 text-sm text-red-500">
                            {{ errors.city }}
                        </p>
                    </div>

                    <!-- POSTAL CODE -->
                    <div>
                        <InputText
                            class="w-full"
                            v-model="form.postalCode"
                            placeholder="Código postal"
                            @blur="validate"
                        />

                        <p
                            v-if="errors.postalCode"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.postalCode }}
                        </p>
                    </div>

                    <!-- SHIPPING ADDRESS -->
                    <div>
                        <InputText
                            class="w-full"
                            v-model="form.shippingAddress"
                            placeholder="Dirección de envío"
                            @blur="validate"
                        />

                        <p
                            v-if="errors.shippingAddress"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.shippingAddress }}
                        </p>
                    </div>

                    <!-- CHECKBOX -->
                    <label
                        class="text-texto-secundario flex items-center gap-2 text-sm"
                    >
                        <input
                            type="checkbox"
                            v-model="form.useShippingAsBilling"
                        />

                        Usar dirección de envío como facturación
                    </label>

                    <!-- BILLING ADDRESS -->
                    <div v-if="!form.useShippingAsBilling">
                        <InputText
                            class="w-full"
                            v-model="form.billingAddress"
                            placeholder="Dirección de facturación"
                            @blur="validate"
                        />

                        <p
                            v-if="errors.billingAddress"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.billingAddress }}
                        </p>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <InputText
                            class="w-full"
                            v-model="form.password"
                            type="password"
                            placeholder="Contraseña"
                            @blur="validate"
                        />

                        <p
                            v-if="errors.password"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.password }}
                        </p>
                    </div>

                    <!-- PASSWORD METER -->
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
                    />

                    <p class="text-texto-secundario text-sm">
                        Seguridad:
                        {{ passwordStrength }}
                    </p>

                    <!-- CONFIRM PASSWORD -->
                    <div>
                        <InputText
                            class="w-full"
                            v-model="form.confirmPassword"
                            type="password"
                            placeholder="Confirmar contraseña"
                            @blur="validate"
                        />

                        <p
                            v-if="errors.confirmPassword"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.confirmPassword }}
                        </p>
                    </div>

                    <!-- SUBMIT -->
                    <button
                        @click="submit"
                        class="bg-azul hover:bg-azul/90 w-full rounded-xl py-3 font-semibold text-white transition"
                    >
                        Registrarse
                    </button>

                    <div class="text-center text-sm">
                        <span class="text-texto-secundario">
                            ¿Ya tienes cuenta?
                        </span>

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

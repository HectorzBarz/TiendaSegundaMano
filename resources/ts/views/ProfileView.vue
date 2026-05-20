<script setup lang="ts">
import { ref, computed } from "vue";
import { useAppToast } from "@/composables/useAppToast";
import Button from "@volt/Button.vue";
import InputText from "@volt/InputText.vue";
import Password from "@volt/Password.vue";
import hampter from "/storage/app/public/img/hampter.jpg";

/**
 * FORM EXTENDED
 */
const user = ref({
    name: "Juan Pérez",
    email: "juan@email.com",
    password: "",
    password_confirmation: "",
    birthDate: "",
    phone: "",
    shippingAddress: "",

    billingAddress: "",
    useShippingAsBilling: false,

    avatar: null,
});

const preview = hampter;
const { show } = useAppToast();

/**
 * REGEX (igual que tu otra vista)
 */
const birthRegex =
    /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19\d{2}|20\d{2})$/;

const phoneRegex = /^\+34\s?[6789]\d{8}$/;

const addressRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,º\-]{5,}$/;

const nameRegex = /^([A-Za-zÁÉÍÓÚáéíóúÑñ'-]+)(\s[A-Za-zÁÉÍÓÚáéíóúÑñ'-]+){1,3}$/;

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

/**
 * ERRORS
 */
const errors = ref<Record<string, string>>({});

const passwordStrength = computed(() => {
    const p = user.value.password;

    if (!p) return "";

    let score = 0;

    if (p.length >= 8) score++;
    if (/[a-z]/.test(p)) score++;
    if (/[A-Z]/.test(p)) score++;
    if (/\d/.test(p)) score++;
    if (/[\W_]/.test(p)) score++;

    if (p.length < 8 || score <= 2) return "Débil";
    if (score === 3 || score === 4) return "Media";

    return "Fuerte";
});

function validate() {
    errors.value = {};

    // NAME
    if (!nameRegex.test(user.value.name)) {
        errors.value.name = "Introduce nombre y apellido válidos";
    }

    // EMAIL
    if (!emailRegex.test(user.value.email)) {
        errors.value.email = "Correo electrónico inválido";
    }

    // PASSWORD
    if (user.value.password) {
        if (passwordStrength.value === "Débil") {
            errors.value.password =
                "La contraseña debe tener seguridad media o fuerte";
        }

        if (user.value.password !== user.value.password_confirmation) {
            errors.value.password_confirmation = "Las contraseñas no coinciden";
        }
    }

    // BIRTH DATE
    if (user.value.birthDate) {
        if (!birthRegex.test(user.value.birthDate)) {
            errors.value.birthDate = "Formato DD/MM/YYYY inválido";
        } else {
            const [d, m, y] = user.value.birthDate.split("/").map(Number);

            const birth = new Date(y, m - 1, d);

            const age = new Date().getFullYear() - birth.getFullYear();

            if (age < 18 || age > 100) {
                errors.value.birthDate = "Debes tener entre 18 y 100 años";
            }
        }
    }

    // PHONE
    if (user.value.phone && !phoneRegex.test(user.value.phone)) {
        errors.value.phone = "Teléfono inválido (+34...)";
    }

    // ADDRESS
    // SHIPPING ADDRESS
    if (
        user.value.shippingAddress &&
        !addressRegex.test(user.value.shippingAddress)
    ) {
        errors.value.shippingAddress = "Dirección inválida";
    }

    // BILLING ADDRESS
    const billingToValidate = user.value.useShippingAsBilling
        ? user.value.shippingAddress
        : user.value.billingAddress;

    if (billingToValidate && !addressRegex.test(billingToValidate)) {
        errors.value.billingAddress = "Dirección de facturación inválida";
    }

    return Object.keys(errors.value).length === 0;
}

const showSuccess = () => {
    if (!validate()) return;

    const finalPayload = {
        ...user.value,

        billingAddress: user.value.useShippingAsBilling
            ? user.value.shippingAddress
            : user.value.billingAddress,
    };

    console.log(finalPayload);

    show({
        message: "¡Cambios guardados!",
        severity: "success",
        life: 3000,
        position: "top-right",
    });
};
</script>

<template>
    <div class="bg-fondo min-h-screen p-6">
        <!-- CONTAINER -->
        <div class="mx-auto max-w-5xl">
            <!-- HEADER -->
            <div
                class="bg-card border-borde mb-8 flex flex-col gap-6 rounded-3xl border p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between"
            >
                <div class="flex items-center gap-5">
                    <!-- AVATAR -->
                    <div class="relative">
                        <img
                            :src="preview"
                            alt="Avatar"
                            class="border-borde h-24 w-24 rounded-3xl border-4 object-cover shadow-sm"
                        />
                    </div>

                    <!-- USER INFO -->
                    <div>
                        <h1
                            class="text-rojo-fuerte text-3xl font-bold tracking-tight"
                        >
                            Configuración de perfil
                        </h1>

                        <p class="text-texto-secundario mt-2">
                            Gestiona tu información personal y credenciales.
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
                    class="border-borde flex items-center justify-between border-b px-8 py-6"
                >
                    <div>
                        <h2 class="text-rojo-fuerte text-2xl font-semibold">
                            Datos del usuario
                        </h2>

                        <p class="text-texto-secundario mt-1 text-sm">
                            Actualiza únicamente los datos que quieras
                            modificar.
                        </p>
                    </div>
                </div>

                <!-- FORM -->
                <div class="grid gap-8 p-8 lg:grid-cols-2">
                    <!-- NAME -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Nombre
                        </label>

                        <InputText
                            v-model="user.name"
                            class="focus:border-azul hover:border-azul rounded-2xl border px-4 py-3 transition"
                            :class="
                                errors.name ? 'border-red-500' : 'border-borde'
                            "
                            @blur="validate"
                        />

                        <p v-if="errors.name" class="text-sm text-red-500">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- EMAIL -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Correo electrónico
                        </label>

                        <InputText
                            v-model="user.email"
                            type="email"
                            class="focus:border-azul hover:border-azul rounded-2xl border px-4 py-3 transition"
                            :class="
                                errors.email ? 'border-red-500' : 'border-borde'
                            "
                            @blur="validate"
                        />

                        <p v-if="errors.email" class="text-sm text-red-500">
                            {{ errors.email }}
                        </p>
                    </div>

                    <!-- PASSWORD -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Nueva contraseña
                        </label>

                        <Password
                            v-model="user.password"
                            toggleMask
                            :feedback="false"
                            class="w-full"
                            inputClass="w-full rounded-2xl border px-4 py-3"
                            :inputProps="{
                                class: errors.password
                                    ? 'border-red-500'
                                    : 'border-borde',
                            }"
                            @blur="validate"
                        />

                        <!-- PASSWORD METER -->
                        <meter
                            v-if="user.password"
                            class="h-2 w-full overflow-hidden rounded-full"
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

                        <!-- PASSWORD STRENGTH -->
                        <p
                            v-if="passwordStrength"
                            class="text-texto-secundario text-sm"
                        >
                            Seguridad: {{ passwordStrength }}
                        </p>

                        <!-- ERROR -->
                        <p v-if="errors.password" class="text-sm text-red-500">
                            {{ errors.password }}
                        </p>
                    </div>

                    <!-- PASSWORD CONFIRMATION -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Confirmar contraseña
                        </label>

                        <Password
                            v-model="user.password_confirmation"
                            toggleMask
                            :feedback="false"
                            class="w-full"
                            inputClass="w-full rounded-2xl border px-4 py-3"
                            @blur="validate"
                        />

                        <p
                            v-if="errors.password_confirmation"
                            class="text-sm text-red-500"
                        >
                            {{ errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- BIRTH DATE -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Fecha de nacimiento
                        </label>

                        <InputText
                            v-model="user.birthDate"
                            placeholder="DD/MM/YYYY"
                            class="border-borde focus:border-azul hover:border-azul rounded-2xl px-4 py-3 transition"
                            @blur="validate"
                        />

                        <p v-if="errors.birthDate" class="text-sm text-red-500">
                            {{ errors.birthDate }}
                        </p>
                    </div>

                    <!-- PHONE -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Teléfono
                        </label>

                        <InputText
                            v-model="user.phone"
                            placeholder="+34 600000000"
                            class="border-borde focus:border-azul hover:border-azul rounded-2xl px-4 py-3 transition"
                            @blur="validate"
                        />

                        <p v-if="errors.phone" class="text-sm text-red-500">
                            {{ errors.phone }}
                        </p>
                    </div>

                    <!-- SHIPPING ADDRESS -->
                    <div class="flex flex-col gap-2 lg:col-span-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Dirección de envío
                        </label>

                        <InputText
                            v-model="user.shippingAddress"
                            placeholder="Calle, número, ciudad, código postal"
                            class="focus:border-azul hover:border-azul rounded-2xl border px-4 py-3 transition"
                            :class="
                                errors.shippingAddress
                                    ? 'border-red-500'
                                    : 'border-borde'
                            "
                            @blur="validate"
                        />

                        <p class="text-texto-secundario text-sm">
                            Ejemplo: Calle Mallorca 401, 3º 2ª, Barcelona
                        </p>

                        <p
                            v-if="errors.shippingAddress"
                            class="text-sm text-red-500"
                        >
                            {{ errors.shippingAddress }}
                        </p>
                    </div>

                    <!-- BILLING CHECKBOX -->
                    <div class="lg:col-span-2">
                        <label
                            class="text-texto-secundario flex items-center gap-3 text-sm"
                        >
                            <input
                                type="checkbox"
                                v-model="user.useShippingAsBilling"
                            />

                            Usar dirección de envío como facturación
                        </label>
                    </div>

                    <!-- BILLING ADDRESS -->
                    <div
                        v-if="!user.useShippingAsBilling"
                        class="flex flex-col gap-2 lg:col-span-2"
                    >
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Dirección de facturación
                        </label>

                        <InputText
                            v-model="user.billingAddress"
                            fluid
                            placeholder="Calle, número, ciudad, código postal"
                            class="focus:border-azul hover:border-azul w-full rounded-2xl border px-4 py-3 transition"
                            :class="
                                errors.billingAddress
                                    ? 'border-red-500'
                                    : 'border-borde'
                            "
                            @blur="validate"
                        />

                        <p class="text-texto-secundario text-sm">
                            Ejemplo: Avenida Diagonal 120, Barcelona
                        </p>

                        <p
                            v-if="errors.billingAddress"
                            class="text-sm text-red-500"
                        >
                            {{ errors.billingAddress }}
                        </p>
                    </div>

                    <!-- IMAGE -->
                    <div class="flex flex-col gap-3 lg:col-span-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Imagen de perfil
                            <span class="text-texto-secundario font-normal">
                                (opcional)
                            </span>
                        </label>

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

                <!-- FOOTER -->
                <div
                    class="border-borde flex flex-col gap-4 border-t bg-slate-50 px-8 py-6 sm:flex-row sm:items-center sm:justify-end"
                >
                    <!-- SUBMIT -->
                    <Button
                        label="Guardar cambios"
                        icon="pi pi-save"
                        class="bg-azul! hover:bg-azul/90! rounded-2xl border-0 px-6 py-3 text-white transition"
                        :class="
                            Object.keys(errors).length
                                ? 'cursor-not-allowed bg-gray-400'
                                : 'bg-azul hover:bg-azul/90'
                        "
                        :disabled="Object.keys(errors).length > 0"
                        @click="showSuccess"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

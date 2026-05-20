<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAppToast } from "@/composables/useAppToast";
import Button from "@volt/Button.vue";
import InputText from "@volt/InputText.vue";
import Password from "@volt/Password.vue";
import { useAuthStore } from "@/stores/auth";

// Instancias
const auth = useAuthStore();
const { show } = useAppToast();
const fileInput = ref<HTMLInputElement | null>(null);

// Estado del Formulario
const userForm = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    birthDate: "",
    phone: "",
    shippingAddress: "",
    billingAddress: "",
    useShippingAsBilling: false,
    avatarFile: null as File | null,
});

// Precarga de datos al montar el componente
onMounted(() => {
    if (auth.user) {
        userForm.value.name = auth.user.name || "";
        userForm.value.email = auth.user.email || "";
        userForm.value.phone = auth.user.phone || "";
        userForm.value.shippingAddress = auth.user.shipping_address || "";
        userForm.value.billingAddress = auth.user.billing_address || "";
        userForm.value.birthDate = auth.user.birth_date || ""; // Formato YYYY-MM-DD
    }
});

const preview = ref(
    auth.user?.profile_image || "/storage/app/public/img/hampter.jpg",
);
const errors = ref<Record<string, string>>({});

// Regex
const birthRegex =
    /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19\d{2}|20\d{2})$/;
const phoneRegex = /^\+34\s?[6789]\d{8}$/;
const addressRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,º\-]{5,}$/;
const nameRegex = /^([A-Za-zÁÉÍÓÚáéíóúÑñ'-]+)(\s[A-Za-zÁÉÍÓÚáéíóúÑñ'-]+){1,3}$/;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Computed
const passwordStrength = computed(() => {
    const p = userForm.value.password;
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

// Validación
function validate() {
    errors.value = {};
    if (!nameRegex.test(userForm.value.name))
        errors.value.name = "Introduce nombre y apellido válidos";
    if (!emailRegex.test(userForm.value.email))
        errors.value.email = "Correo electrónico inválido";

    if (userForm.value.password) {
        if (passwordStrength.value === "Débil")
            errors.value.password =
                "La contraseña debe tener seguridad media o fuerte";
        if (userForm.value.password !== userForm.value.password_confirmation)
            errors.value.password_confirmation = "Las contraseñas no coinciden";
    }

    if (userForm.value.phone && !phoneRegex.test(userForm.value.phone))
        errors.value.phone = "Teléfono inválido (+34...)";

    // Validar direcciones
    if (
        userForm.value.shippingAddress &&
        !addressRegex.test(userForm.value.shippingAddress)
    )
        errors.value.shippingAddress = "Dirección inválida";

    const billingToValidate = userForm.value.useShippingAsBilling
        ? userForm.value.shippingAddress
        : userForm.value.billingAddress;
    if (billingToValidate && !addressRegex.test(billingToValidate))
        errors.value.billingAddress = "Dirección de facturación inválida";

    return Object.keys(errors.value).length === 0;
}

// Lógica de archivo
function triggerFileInput() {
    fileInput.value?.click();
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        userForm.value.avatarFile = target.files[0];
        preview.value = URL.createObjectURL(target.files[0]);
    }
}

// Guardar Cambios
const saveChanges = async () => {
    if (!validate()) return;

    const formData = new FormData();
    formData.append("name", userForm.value.name);
    formData.append("email", userForm.value.email);
    formData.append("phone", userForm.value.phone);
    formData.append("birth_date", userForm.value.birthDate);
    formData.append("shipping_address", userForm.value.shippingAddress);
    formData.append(
        "billing_address",
        userForm.value.useShippingAsBilling
            ? userForm.value.shippingAddress
            : userForm.value.billingAddress,
    );

    if (userForm.value.password) {
        formData.append("password", userForm.value.password);
        formData.append(
            "password_confirmation",
            userForm.value.password_confirmation,
        );
    }
    if (userForm.value.avatarFile) {
        formData.append("profile_image", userForm.value.avatarFile);
    }

    try {
        await auth.updateProfile(formData);
        show({
            message: "¡Perfil actualizado!",
            severity: "success",
            life: 3000,
        });
    } catch (e) {
        show({ message: "Error al actualizar los datos", severity: "error" });
    }
};
</script>

<template>
    <div class="bg-fondo min-h-screen p-6">
        <div class="mx-auto max-w-5xl">
            <div
                class="bg-card border-borde mb-8 flex flex-col gap-6 rounded-3xl border p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between"
            >
                <div class="flex items-center gap-5">
                    <div
                        class="relative cursor-pointer"
                        @click="triggerFileInput"
                    >
                        <img
                            :src="preview"
                            alt="Avatar"
                            class="border-borde h-24 w-24 rounded-3xl border-4 object-cover shadow-sm transition hover:opacity-80"
                        />
                        <input
                            type="file"
                            ref="fileInput"
                            class="hidden"
                            accept="image/*"
                            @change="handleFileChange"
                        />
                    </div>
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

            <div
                class="bg-card border-borde overflow-hidden rounded-3xl border shadow-sm"
            >
                <div
                    class="border-borde flex items-center justify-between border-b px-8 py-6"
                >
                    <h2 class="text-rojo-fuerte text-2xl font-semibold">
                        Datos del usuario
                    </h2>
                </div>

                <div class="grid gap-8 p-8 lg:grid-cols-2">
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Nombre</label
                        >
                        <InputText
                            v-model="userForm.name"
                            class="rounded-2xl border px-4 py-3"
                            :class="
                                errors.name ? 'border-red-500' : 'border-borde'
                            "
                            @blur="validate"
                        />
                        <p v-if="errors.name" class="text-sm text-red-500">
                            {{ errors.name }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Correo electrónico</label
                        >
                        <InputText
                            v-model="userForm.email"
                            type="email"
                            class="rounded-2xl border px-4 py-3"
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
                            v-model="userForm.password"
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

                        <meter
                            v-if="userForm.password"
                            class="h-2 w-full overflow-hidden rounded-full"
                            min="0"
                            max="3"
                            :value="
                                passwordStrength === 'Débil'
                                    ? 1
                                    : passwordStrength === 'Media'
                                      ? 2
                                      : passwordStrength === 'Fuerte'
                                        ? 3
                                        : 0
                            "
                        ></meter>

                        <p
                            v-if="passwordStrength"
                            class="text-texto-secundario text-sm"
                        >
                            Seguridad: {{ passwordStrength }}
                        </p>

                        <p v-if="errors.password" class="text-sm text-red-500">
                            {{ errors.password }}
                        </p>
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Confirmar contraseña</label
                        >
                        <Password
                            v-model="userForm.password_confirmation"
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

                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Fecha de nacimiento</label
                        >
                        <InputText
                            v-model="userForm.birthDate"
                            placeholder="DD/MM/YYYY"
                            class="border-borde rounded-2xl px-4 py-3"
                            @blur="validate"
                        />
                        <p v-if="errors.birthDate" class="text-sm text-red-500">
                            {{ errors.birthDate }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Teléfono</label
                        >
                        <InputText
                            v-model="userForm.phone"
                            placeholder="+34 600000000"
                            class="border-borde rounded-2xl px-4 py-3"
                            @blur="validate"
                        />
                        <p v-if="errors.phone" class="text-sm text-red-500">
                            {{ errors.phone }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2 lg:col-span-2">
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Dirección de envío</label
                        >
                        <InputText
                            v-model="userForm.shippingAddress"
                            placeholder="Calle, número, ciudad..."
                            class="rounded-2xl border px-4 py-3"
                            :class="
                                errors.shippingAddress
                                    ? 'border-red-500'
                                    : 'border-borde'
                            "
                            @blur="validate"
                        />
                        <p
                            v-if="errors.shippingAddress"
                            class="text-sm text-red-500"
                        >
                            {{ errors.shippingAddress }}
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                        <label
                            class="text-texto-secundario flex items-center gap-3 text-sm"
                        >
                            <input
                                type="checkbox"
                                v-model="userForm.useShippingAsBilling"
                            />
                            Usar dirección de envío como facturación
                        </label>
                    </div>

                    <div
                        v-if="!userForm.useShippingAsBilling"
                        class="flex flex-col gap-2 lg:col-span-2"
                    >
                        <label class="text-rojo-fuerte text-sm font-semibold"
                            >Dirección de facturación</label
                        >
                        <InputText
                            v-model="userForm.billingAddress"
                            placeholder="Calle, número, ciudad..."
                            class="rounded-2xl border px-4 py-3"
                            @blur="validate"
                        />
                    </div>
                </div>

                <div
                    class="border-borde flex items-center justify-end border-t bg-slate-50 px-8 py-6"
                >
                    <Button
                        label="Guardar cambios"
                        icon="pi pi-save"
                        class="bg-azul! rounded-2xl px-6 py-3 text-white"
                        @click="saveChanges"
                        :disabled="Object.keys(errors).length > 0"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

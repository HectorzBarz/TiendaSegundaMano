<script setup lang="ts">
import { ref } from "vue";

import Button from "@volt/Button.vue";

import InputText from "@volt/InputText.vue";
import Password from "@volt/Password.vue";

import hampter from "/storage/app/public/img/hampter.jpg";

const user = ref({
    name: "Juan Pérez",
    email: "juan@email.com",
    password: "",
    password_confirmation: "",
    avatar: null,
});

const preview = hampter;
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
                            class="border-borde focus:border-azul hover:border-azul rounded-2xl px-4 py-3 transition"
                        />
                    </div>

                    <!-- EMAIL -->
                    <div class="flex flex-col gap-2">
                        <label class="text-rojo-fuerte text-sm font-semibold">
                            Correo electrónico
                        </label>

                        <InputText
                            v-model="user.email"
                            type="email"
                            class="border-borde focus:border-azul hover:border-azul rounded-2xl px-4 py-3 transition"
                        />
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
                            inputClass="w-full rounded-2xl border-borde px-4 py-3"
                        />
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
                            inputClass="w-full rounded-2xl border-borde px-4 py-3"
                        />
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
                    class="border-borde flex flex-col gap-4 border-t bg-slate-50 px-8 py-6 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p class="text-rojo-fuerte font-semibold">
                            Seguridad de la cuenta
                        </p>

                        <p class="text-texto-secundario text-sm">
                            Recomendamos actualizar tu contraseña regularmente.
                        </p>
                    </div>

                    <!-- SUBMIT -->
                    <Button
                        label="Guardar cambios"
                        icon="pi pi-save"
                        class="bg-azul! hover:bg-azul/90! rounded-2xl border-0 px-6 py-3 text-white transition"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

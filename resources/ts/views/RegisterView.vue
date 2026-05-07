<script setup lang="ts">
import InputText from "@volt/InputText.vue";
import { RouterLink } from "vue-router";
</script>

<template>
    <main
        class="flex min-h-screen items-center justify-center bg-gray-100 px-4"
    >
        <section
            class="mb-5 w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl"
        >
            <div class="grid md:grid-cols-2">
                <!-- FORM -->
                <div class="flex flex-col justify-center p-10">
                    <h1
                        class="mb-8 text-center text-4xl font-bold text-gray-800"
                    >
                        Registrarse
                    </h1>

                    <div class="space-y-5">
                        <InputText
                            class="w-full rounded-xl border border-gray-300 p-3"
                            placeholder="Nombre"
                        />
                        <InputText
                            class="w-full rounded-xl border border-gray-300 p-3"
                            placeholder="Correo"
                        />

                        <InputText
                            class="w-full rounded-xl border border-gray-300 p-3"
                            placeholder="Contraseña"
                        />
                        <InputText
                            class="w-full rounded-xl border border-gray-300 p-3"
                            placeholder="Confirma contraseña"
                        />

                        <button
                            class="w-full rounded-xl bg-black py-3 text-white transition hover:bg-gray-800"
                        >
                            Entrar
                        </button>

                        <!-- REGISTER LINK -->
                        <div class="text-center">
                            <span class="text-gray-500">
                                ¿Ya tienes cuenta?
                            </span>

                            <RouterLink
                                to="/login"
                                class="ml-2 font-semibold text-black transition hover:text-gray-700 hover:underline"
                            >
                                Inicia sesión
                            </RouterLink>
                        </div>
                    </div>
                </div>

                <!-- IMAGE -->
                <div class="flex items-center justify-center bg-gray-50 p-10">
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

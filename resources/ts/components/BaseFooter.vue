<script setup lang="ts">
import { useCopyToClipboard } from "@/composables/useCopyToClipboard";

const emailToCopy = "hectorrodriguezbar99@gmail.com";

const { copyToClipboard } = useCopyToClipboard(emailToCopy);
</script>

<template>
    <footer class="border-borde bg-card mt-20 border-t shadow-sm">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <!-- TOP -->
            <div
                class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between"
            >
                <!-- INFO -->
                <div class="space-y-3">
                    <h2 class="text-rojo-fuerte text-2xl font-bold">
                        Héctor Rodríguez
                    </h2>

                    <p class="text-texto-secundario max-w-md leading-relaxed">
                        Aplicación desarrollada con Vue 3, TypeScript,
                        TailwindCSS y Volt UI.
                    </p>

                    <p class="text-texto-secundario text-sm">
                        © 2026 Todos los derechos reservados.
                    </p>
                </div>

                <!-- SOCIAL -->
                <div class="space-y-4">
                    <p class="text-rojo-fuerte font-semibold">
                        Contacto y redes
                    </p>

                    <div class="flex gap-4">
                        <!-- INSTAGRAM -->
                        <a
                            href="https://www.instagram.com/hectorzbarz/"
                            target="_blank"
                            class="bg-amarillo/20 text-rojo-claro hover:bg-rojo-claro flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200 hover:text-white"
                        >
                            <i class="pi pi-instagram text-xl"></i>
                        </a>

                        <!-- GITHUB -->
                        <a
                            href="https://github.com/HectorzBarz"
                            target="_blank"
                            class="text-texto hover:bg-texto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 transition-all duration-200 hover:text-white"
                        >
                            <i class="pi pi-github text-xl"></i>
                        </a>

                        <!-- LINKEDIN -->
                        <a
                            href="https://www.linkedin.com"
                            target="_blank"
                            class="bg-azul/10 text-azul hover:bg-azul flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200 hover:text-white"
                        >
                            <i class="pi pi-linkedin text-xl"></i>
                        </a>

                        <!-- EMAIL -->
                        <button
                            @click="copyToClipboard"
                            class="bg-naranja/10 text-naranja hover:bg-naranja flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-200 hover:text-white"
                        >
                            <i class="pi pi-envelope text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- MAP -->
            <div class="mt-10">
                <iframe
                    class="border-borde h-72 w-full rounded-3xl border shadow-sm"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26184.37697069079!2d27.40146889000398!3d54.54418292577938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46db792a595410bd%3A0x19558da5b280ebc3!2z0KbQsNGA0LrQstCwINCf0YDQsNCw0LHRgNCw0LbRjdC90L3RjyDQk9Cw0YHQv9C-0LTQvdGP0LPQsA!5e0!3m2!1ses!2ses!4v1776188916217!5m2!1ses!2ses"
                ></iframe>
            </div>
        </div>
    </footer>
</template>

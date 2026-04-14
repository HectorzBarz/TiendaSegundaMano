<script setup lang="ts">
import { useCopyToClipboard } from "@/composables/useCopyToClipboard";

const emailToCopy = "hectorrodriguezbar99@gmail.com";

const { copyToClipboard } = useCopyToClipboard(emailToCopy);
</script>

<template>
    <footer class="flex flex-col bg-linear-to-b from-gray-100 to-gray-300 p-5">
        <div class="flex items-center justify-between">
            <div>Héctor Rodríguez Barroso 2026</div>
            <div class="flex flex-col gap-2">
                <span>Puedes contactarme a través de:</span>
                <div class="flex justify-around">
                    <a
                        href="https://www.instagram.com/hectorzbarz/"
                        target="_blank"
                    >
                        <i
                            style="font-size: 1.5rem"
                            class="pi-instagram pi cursor-pointer rounded-2xl bg-linear-to-b hover:from-red-500 hover:to-yellow-200 hover:text-pink-600 hover:transition-colors hover:duration-300"
                        />
                    </a>
                    <a href="https://github.com/HectorzBarz" target="_blank">
                        <i
                            style="font-size: 1.5rem"
                            class="pi-github pi cursor-pointer hover:text-purple-500 hover:transition-colors hover:duration-300"
                        />
                    </a>
                    <a
                        href="https://www.linkedin.com/in/h%C3%A9ctor-rodr%C3%ADguez-barroso-96465b388/"
                        target="_blank"
                    >
                        <i
                            style="font-size: 1.5rem"
                            class="pi-linkedin pi cursor-pointer hover:text-blue-500 hover:transition-colors hover:duration-300"
                        />
                    </a>
                    <i
                        style="font-size: 1.5rem"
                        class="pi-envelope pi cursor-pointer hover:text-orange-300 hover:transition-colors hover:duration-300"
                        @click="copyToClipboard"
                    />
                </div>
            </div>
        </div>
        <div class="mt-4">
            <iframe
                class="h-64 w-full rounded-lg"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26184.37697069079!2d27.40146889000398!3d54.54418292577938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46db792a595410bd%3A0x19558da5b280ebc3!2z0KbQsNGA0LrQstCwINCf0YDQsNCw0LHRgNCw0LbRjdC90L3RjyDQk9Cw0YHQv9C-0LTQvdGP0LPQsA!5e0!3m2!1ses!2ses!4v1776188916217!5m2!1ses!2ses"
            ></iframe>
        </div>
    </footer>
</template>

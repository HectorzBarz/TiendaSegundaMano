<script setup lang="ts">
import { useCopyToClipboard } from "@/composables/useCopyToClipboard";

const emailToCopy = "hectorrodriguezbar99@gmail.com";

const { copyToClipboard } = useCopyToClipboard(emailToCopy);
</script>

<template>
    <footer
        class="flex items-center justify-between bg-linear-to-b from-gray-100 to-gray-300 p-5"
    >
        <div>Todos los derechos están reservados a mí</div>
        <div class="flex flex-col gap-2">
            <span>Puedes contactarme a través de:</span>
            <div class="flex justify-around">
                <a
                    href="https://www.instagram.com/hectorzbarz/"
                    target="_blank"
                >
                    <i
                        style="font-size: 1.5rem"
                        class="pi-instagram pi cursor-pointer hover:text-red-500 hover:transition-colors hover:duration-300"
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
    </footer>
</template>

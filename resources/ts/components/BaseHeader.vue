<script setup lang="ts"></script>

<template>
    <div
        class="grid grid-cols-3 items-center bg-gray-200 h-20 content-center shadow-2xl"
    >
        <div class="px-10"><img src="" alt="logo" /></div>
        <nav class="flex justify-around">
            <RouterLink to="/">
                <ul
                    class="px-5 py-2 hover:scale-110 transition-all hover:rounded-b-lg hover:shadow-lg hover:cursor-pointer"
                >
                    Inicio
                </ul>
            </RouterLink>
            <RouterLink to="/articles">
                <ul
                    class="px-5 py-2 hover:scale-110 transition-all hover:rounded-b-lg hover:shadow-lg hover:cursor-pointer"
                >
                    Productos
                </ul>
            </RouterLink>
            <ul
                class="px-5 py-2 hover:scale-110 transition-all hover:rounded-b-lg hover:shadow-lg hover:cursor-pointer"
            >
                Categorias
            </ul>
            <ul
                class="px-5 py-2 hover:scale-110 transition-all hover:rounded-b-lg hover:shadow-lg hover:cursor-pointer"
            >
                Área personal
            </ul>
        </nav>
        <div class="hidden"></div>
    </div>
</template>

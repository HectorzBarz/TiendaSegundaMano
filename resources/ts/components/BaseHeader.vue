<script setup lang="ts"></script>

<template>
    <div
        class="grid h-20 grid-cols-3 content-center items-center bg-linear-to-t from-gray-100 to-gray-200"
    >
        <RouterLink to="/">
            <div class="px-10">
                <img
                    src="/public/img/favicon.svg"
                    alt="logo"
                    class="h-16 w-fit"
                /></div
        ></RouterLink>
        <nav class="flex justify-around">
            <RouterLink to="/">
                <ul
                    class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                >
                    Inicio
                </ul>
            </RouterLink>
            <RouterLink to="/articles">
                <ul
                    class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                >
                    Productos
                </ul>
            </RouterLink>

            <RouterLink to="/categories">
                <ul
                    class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
                >
                    Categorias
                </ul>
            </RouterLink>
            <ul
                class="px-5 py-2 transition-all hover:scale-110 hover:cursor-pointer hover:rounded-b-lg hover:shadow-lg"
            >
                Área personal
            </ul>
        </nav>
        <div class="hidden"></div>
    </div>
</template>

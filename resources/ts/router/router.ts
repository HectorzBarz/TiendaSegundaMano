import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        name: "home",
        component: () => import("@/views/HomeView.vue"),
    },
    {
        path: "/category/:id",
        name: "category",
        component: () => import("@/views/CategoryView.vue"),
        meta: { title: "Category" },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

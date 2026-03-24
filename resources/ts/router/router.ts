import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        name: "home",
        component: () => import("@/views/HomeView.vue"),
    },
    {
        path: "/articles/:catgory?",
        name: "articles",
        component: () => import("@/views/ArticlesView.vue"),
        meta: { title: "Articles" },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

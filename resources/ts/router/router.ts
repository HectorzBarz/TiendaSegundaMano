import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        name: "home",
        component: () => import("@/views/HomeView.vue"),
    },
    {
        path: "/articles/:categoryId?",
        name: "articles",
        component: () => import("@/views/ArticlesView.vue"),
        meta: { title: "Articles" },
        props: (route: any) => ({
            categoryId: route.params.categoryId
                ? Number(route.params.categoryId)
                : 0,
        }),
    },
    {
        path: "/categories",
        name: "categories",
        component: () => import("@/views/CategoriesView.vue"),
        meta: { title: "Categorías" },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

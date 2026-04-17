import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        name: "home",
        component: () => import("@/views/HomeView.vue"),
    },
    {
        path: "/articles/",
        name: "articles",
        component: () => import("@/views/ArticlesView.vue"),
        meta: { title: "Articles" },
    },
    {
        path: "/articles/:id",
        name: "articles/:id",
        component: () => import("@/views/ArticleDetailsView.vue"),
        meta: { title: "Article" },
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
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }

        return { top: 0 };
    },
});

export default router;

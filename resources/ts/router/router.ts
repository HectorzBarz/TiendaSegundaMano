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
        path: "/article-query/:id",
        name: "article-query",
        component: () => import("@/views/ArticleQueryView.vue"),
        meta: { title: "ArticleQuery" },
    },
    {
        path: "/categories",
        name: "categories",
        component: () => import("@/views/CategoriesView.vue"),
        meta: { title: "Categorías" },
    },
    {
        path: "/login",
        name: "login",
        component: () => import("@/views/LoginView.vue"),
        meta: { title: "Login" },
    },
    {
        path: "/register",
        name: "register",
        component: () => import("@/views/RegisterView.vue"),
        meta: { title: "Register" },
    },
    {
        path: "/account",
        name: "account",
        component: () => import("@/views/AccountView.vue"),
        meta: { title: "Account" },
    },
    {
        path: "/account/profile",
        name: "profile",
        component: () => import("@/views/ProfileView.vue"),
        meta: { title: "Profile" },
    },
    {
        path: "/account/history",
        name: "history",
        component: () => import("@/views/ShoppingHistoryView.vue"),
        meta: { title: "History" },
    },
    {
        path: "/account/wishlist",
        name: "wishlist",
        component: () => import("@/views/WishlistView.vue"),
        meta: { title: "Wishlist" },
    },
    {
        path: "/admin",
        name: "admin",
        component: () => import("@/views/AdminPanelView.vue"),
        meta: { title: "Admin" },
    },
    {
        path: "/admin/create",
        name: "create",
        component: () => import("@/views/AddArticleView.vue"),
        meta: { title: "Create Article" },
    },
    {
        path: "/admin/category",
        name: "category",
        component: () => import("@/views/CreateCategoryView.vue"),
        meta: { title: "Create Category" },
    },
    {
        path: "/admin/users/:id/edit", // Nueva ruta específica para admins
        name: "admin-edit-user",
        component: () => import("@/views/ProfileView.vue"), // Reutilizamos tu vista
        meta: { title: "Edit User" },
    },
    {
        path: "/checkout",
        name: "Checkout",
        component: () => import("@/views/CheckoutView.vue"),
        meta: { title: "Checkout" },
    },
    {
        path: "/admin/articles/:id/edit",
        name: "edit",
        component: () => import("@/views/EditArticleView.vue"),
        meta: { title: "Edit Article" },
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

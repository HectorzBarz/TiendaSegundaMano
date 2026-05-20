// src/stores/auth.ts
import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axios from "axios";

// Configura la URL base de tu API de Laravel
const api = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

// Interceptor para inyectar el token en cada petición automáticamente
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("auth_token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export const useAuthStore = defineStore("auth", () => {
    const user = ref(null);
    const token = ref(localStorage.getItem("auth_token") || null);

    const isAuthenticated = computed(() => !!token.value);

    async function login(credentials: any) {
        const response = await api.post("/login", credentials);
        setSession(response.data.token, response.data.user);
    }

    async function register(data: any) {
        const response = await api.post("/register", data);
        setSession(response.data.token, response.data.user);
    }

    async function logout() {
        try {
            await api.post("/logout");
        } catch (e) {
            console.error(e);
        } finally {
            clearSession();
        }
    }

    function setSession(newToken: string, userData: any) {
        token.value = newToken;
        user.value = userData;
        localStorage.setItem("auth_token", newToken);
    }

    function clearSession() {
        token.value = null;
        user.value = null;
        localStorage.removeItem("auth_token");
    }

    return { user, token, isAuthenticated, login, register, logout };
});

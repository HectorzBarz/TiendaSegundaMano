// src/stores/auth.ts
import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axios from "axios";
import { User } from "@/types";

// 1. Definimos la interfaz (molde) de lo que es un Usuario en tu aplicación

// Configura la URL base de tu API de Laravel
export const api = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        Accept: "application/json",
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

export const useAuthStore = defineStore(
    "auth",
    () => {
        // 2. Le indicamos a TypeScript que 'user' puede ser de tipo 'User' o 'null'
        const user = ref<User | null>(null);
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

        // 3. Tipamos 'userData' para que coincida con nuestra interfaz
        function setSession(newToken: string, userData: User) {
            token.value = newToken;
            user.value = userData;
            localStorage.setItem("auth_token", newToken);
        }

        function clearSession() {
            token.value = null;
            user.value = null;
            localStorage.removeItem("auth_token");
        }

        async function updateProfile(formData: FormData) {
            // Usamos 'POST' con el header multipart para que Laravel gestione el archivo
            const response = await api.post("/user/profile", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            // Actualizamos el usuario en la store con los datos frescos del servidor
            user.value = response.data.user;
            return response.data;
        }

        async function getUserById(id: number) {
            const response = await api.get(`/admin/users/${id}`);
            return response.data;
        }

        async function updateUserByAdmin(id: number, formData: FormData) {
            const response = await api.post(`/admin/users/${id}`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            return response.data;
        }

        async function getUsers() {
            const response = await api.get("/admin/users");
            return response.data;
        }

        async function makeAdmin(id: number) {
            const response = await api.patch(`/admin/users/${id}/make-admin`);

            return response.data;
        }

        async function removeAdmin(id: number) {
            const response = await api.patch(`/admin/users/${id}/remove-admin`);

            return response.data;
        }

        const deleteUser = async (id: number) => {
            return await api.delete(`/admin/users/${id}`);
        };

        return {
            user,
            token,
            isAuthenticated,
            login,
            register,
            logout,
            updateProfile,
            getUserById,
            updateUserByAdmin,
            getUsers,
            makeAdmin,
            removeAdmin,
            deleteUser,
        };
    },
    {
        persist: true,
    },
);

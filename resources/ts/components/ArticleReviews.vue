<script setup lang="ts">
import { ref, onMounted } from "vue";
import { api, useAuthStore } from "@/stores/auth";

const props = defineProps<{
    articleId: number;
}>();

const emit = defineEmits(["review-added", "review-deleted"]); // Añadimos el emit de borrado por si acaso

const authStore = useAuthStore();

interface UserPublic {
    name: string;
    profile_image: string | null;
}

interface Review {
    id: number;
    user_id: number; // 👈 Clave para saber quién creó la reseña
    rating: number;
    comment: string | null;
    created_at: string;
    user?: UserPublic;
}

const reviews = ref<Review[]>([]);
const rating = ref(5);
const comment = ref("");
const submitting = ref(false);
const loadingReviews = ref(false);
const errorMessage = ref("");

const fetchReviews = async () => {
    try {
        loadingReviews.value = true;
        const { data } = await api.get(`/articles/${props.articleId}/reviews`);
        reviews.value = Array.isArray(data) ? data : [];
    } catch (err) {
        console.error("Error al cargar comentarios:", err);
    } finally {
        loadingReviews.value = false;
    }
};

const submitReview = async () => {
    if (submitting.value) return;
    errorMessage.value = "";
    submitting.value = true;

    try {
        const { data } = await api.post(
            `/articles/${props.articleId}/reviews`,
            {
                rating: rating.value,
                comment: comment.value,
            },
        );

        if (data && data.id) {
            // Si el backend no te devuelve el 'user_id' o 'user' al crear, se lo inyectamos localmente
            if (!data.user_id && authStore.user)
                data.user_id = authStore.user.id;
            if (!data.user && authStore.user) {
                data.user = {
                    name: authStore.user.name,
                    profile_image: authStore.user.profile_image,
                };
            }
            reviews.value.unshift(data);
        }

        comment.value = "";
        rating.value = 5;
        emit("review-added");
    } catch (err: any) {
        console.error("Error al publicar la reseña:", err);
        errorMessage.value =
            err.response?.data?.message ||
            "Ocurrió un error al enviar tu reseña.";
    } finally {
        submitting.value = false;
    }
};

// 🗑️ Función para eliminar la reseña
const deleteReview = async (reviewId: number) => {
    if (!confirm("¿Estás seguro de que quieres eliminar tu opinión?")) return;

    try {
        await api.delete(`/reviews/${reviewId}`);
        // Quitamos la reseña de la pantalla instantáneamente
        reviews.value = reviews.value.filter((rev) => rev.id !== reviewId);
        emit("review-deleted");
    } catch (err: any) {
        console.error("Error al eliminar la reseña:", err);
        alert(
            err.response?.data?.message || "No se pudo eliminar el comentario.",
        );
    }
};

onMounted(fetchReviews);
</script>

<template>
    <section class="mt-16 border-t pt-10">
        <h2 class="text-rojo-fuerte mb-6 text-2xl font-bold">
            Opiniones de los compradores
        </h2>

        <div class="grid gap-10 md:grid-cols-3">
            <!-- FORMULARIO DE NUEVA RESEÑA -->
            <div
                class="border-borde h-fit rounded-2xl border bg-white p-6 shadow-sm md:col-span-1"
            >
                <h3 class="text-texto mb-4 text-lg font-semibold">
                    Deja tu opinión
                </h3>

                <!-- Solo permitimos comentar si está logueado -->
                <form
                    v-if="authStore.isAuthenticated"
                    @submit.prevent="submitReview"
                    class="space-y-4"
                >
                    <div>
                        <label class="text-texto mb-2 block text-sm font-medium"
                            >Puntuación</label
                        >
                        <div class="flex gap-2">
                            <button
                                v-for="star in 5"
                                :key="star"
                                type="button"
                                @click="rating = star"
                                class="text-2xl transition-transform active:scale-95"
                            >
                                <i
                                    :class="
                                        star <= rating
                                            ? 'pi pi-star-fill text-yellow-400'
                                            : 'pi pi-star text-gray-300'
                                    "
                                ></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label
                            for="comment"
                            class="text-texto mb-1 block text-sm font-medium"
                            >Comentario</label
                        >
                        <textarea
                            id="comment"
                            v-model="comment"
                            rows="4"
                            placeholder="¿Qué te pareció el artículo?..."
                            class="focus:border-azul w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none"
                        ></textarea>
                    </div>

                    <p
                        v-if="errorMessage"
                        class="rounded-lg bg-red-50 p-2 text-xs text-red-600"
                    >
                        {{ errorMessage }}
                    </p>

                    <button
                        type="submit"
                        :disabled="submitting"
                        class="bg-azul hover:bg-azul/90 w-full rounded-xl py-2.5 text-sm font-semibold text-white transition disabled:opacity-50"
                    >
                        {{ submitting ? "Enviando..." : "Publicar reseña" }}
                    </button>
                </form>
                <div
                    v-else
                    class="text-texto-secundario py-4 text-center text-sm"
                >
                    Necesitas iniciar sesión para dejar una opinión.
                </div>
            </div>

            <!-- LISTADO DE COMENTARIOS -->
            <div class="space-y-4 md:col-span-2">
                <div
                    v-if="loadingReviews"
                    class="flex items-center justify-center py-10"
                >
                    <i class="pi pi-spinner pi-spin text-azul text-3xl"></i>
                </div>

                <div
                    v-else-if="reviews.length === 0"
                    class="text-texto-secundario rounded-2xl border border-dashed bg-gray-50 py-10 text-center"
                >
                    <i class="pi pi-comments mb-2 block text-3xl"></i>
                    Aún no hay opiniones sobre este artículo. ¡Sé el primero!
                </div>

                <div
                    v-else
                    v-for="rev in reviews"
                    :key="rev.id"
                    class="bg-card border-borde group relative flex gap-4 rounded-2xl border p-5 shadow-2xs"
                >
                    <div
                        class="h-10 w-10 shrink-0 overflow-hidden rounded-full border bg-gray-200"
                    >
                        <img
                            v-if="rev.user?.profile_image"
                            :src="rev.user.profile_image"
                            class="h-full w-full object-cover"
                        />
                        <div
                            v-else
                            class="flex h-full w-full items-center justify-center text-xs font-bold text-gray-500 uppercase"
                        >
                            {{ rev.user?.name ? rev.user.name.charAt(0) : "U" }}
                        </div>
                    </div>

                    <div class="flex-1">
                        <div
                            class="flex flex-wrap items-center justify-between gap-2 pr-6"
                        >
                            <h4 class="text-texto font-semibold">
                                {{ rev.user?.name || "Usuario Anónimo" }}
                            </h4>
                            <span
                                class="text-texto-secundario text-xs"
                                v-if="rev.created_at"
                            >
                                {{
                                    new Date(
                                        rev.created_at,
                                    ).toLocaleDateString()
                                }}
                            </span>
                        </div>

                        <div class="my-1 flex gap-0.5 text-sm">
                            <i
                                v-for="star in 5"
                                :key="star"
                                :class="
                                    star <= rev.rating
                                        ? 'pi pi-star-fill text-yellow-400'
                                        : 'pi pi-star text-gray-200'
                                "
                            ></i>
                        </div>

                        <p
                            class="text-texto-secundario mt-2 text-sm whitespace-pre-line"
                        >
                            {{ rev.comment }}
                        </p>
                    </div>

                    <!-- 🛑 BOTÓN DE ELIMINAR (Solo visible para el creador del comentario) -->
                    <button
                        v-if="
                            authStore.isAuthenticated &&
                            authStore.user &&
                            rev.user_id === authStore.user.id
                        "
                        @click="deleteReview(rev.id)"
                        class="absolute top-4 right-4 p-1 text-gray-400 transition-colors hover:text-red-500"
                        title="Eliminar comentario"
                    >
                        <i class="pi pi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

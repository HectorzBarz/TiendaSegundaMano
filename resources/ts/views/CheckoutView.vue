<script setup lang="ts">
import { useCartStore } from "@/stores/cart";
import { useAuthStore, api } from "@/stores/auth";
import { useRouter } from "vue-router";
import { ref, computed, watch } from "vue";

const cart = useCartStore();
const auth = useAuthStore();
const router = useRouter();

if (!auth.isAuthenticated) {
    router.push("/login");
}
if (cart.items.length === 0) {
    router.push("/");
}

const loading = ref(false);
const error = ref("");
const success = ref(false);
const orderId = ref<number | null>(null);

const sameAsShipping = ref(true);

const form = ref({
    shipping_name: auth.user?.name || "",
    shipping_address: auth.user?.shipping_address || "",
    shipping_city: auth.user?.city || "",
    shipping_province: "",
    shipping_postal_code: auth.user?.postal_code || "",
    shipping_phone: auth.user?.phone || "",
    billing_name: "",
    billing_address: "",
    billing_city: "",
    billing_province: "",
    billing_postal_code: "",
    billing_phone: "",
    card_number: "",
    card_expiry: "",
    card_cvv: "",
});

watch(sameAsShipping, (val) => {
    if (val) {
        form.value.billing_name = "";
        form.value.billing_address = "";
        form.value.billing_city = "";
        form.value.billing_province = "";
        form.value.billing_postal_code = "";
        form.value.billing_phone = "";
    }
});

const fieldErrors = ref<Record<string, string>>({});

function validate(): boolean {
    const errors: Record<string, string> = {};
    const f = form.value;

    if (!f.shipping_name.trim()) errors.shipping_name = "Requerido";
    if (!f.shipping_address.trim()) errors.shipping_address = "Requerido";
    if (!f.shipping_city.trim()) errors.shipping_city = "Requerido";
    if (!f.shipping_province.trim()) errors.shipping_province = "Requerido";
    if (!f.shipping_postal_code.trim()) errors.shipping_postal_code = "Requerido";
    if (!f.shipping_phone.trim()) errors.shipping_phone = "Requerido";

    if (!sameAsShipping.value) {
        if (!f.billing_name.trim()) errors.billing_name = "Requerido";
        if (!f.billing_address.trim()) errors.billing_address = "Requerido";
        if (!f.billing_city.trim()) errors.billing_city = "Requerido";
        if (!f.billing_province.trim()) errors.billing_province = "Requerido";
        if (!f.billing_postal_code.trim()) errors.billing_postal_code = "Requerido";
        if (!f.billing_phone.trim()) errors.billing_phone = "Requerido";
    }

    const cardNum = f.card_number.replace(/\s/g, "");
    if (!/^\d{16}$/.test(cardNum)) {
        errors.card_number = "Debe tener 16 dígitos";
    }
    if (!/^\d{2}\/\d{2,4}$/.test(f.card_expiry)) {
        errors.card_expiry = "Formato MM/AA";
    }
    if (!/^\d{3}$/.test(f.card_cvv)) {
        errors.card_cvv = "Debe tener 3 dígitos";
    }

    fieldErrors.value = errors;
    return Object.keys(errors).length === 0;
}

function formatCardNumber() {
    let val = form.value.card_number.replace(/\D/g, "").slice(0, 16);
    form.value.card_number = val.replace(/(.{4})/g, "$1 ").trim();
}

function formatExpiry() {
    let val = form.value.card_expiry.replace(/\D/g, "").slice(0, 4);
    if (val.length >= 2) {
        val = val.slice(0, 2) + "/" + val.slice(2);
    }
    form.value.card_expiry = val;
}

async function pay() {
    if (!validate()) return;
    loading.value = true;
    error.value = "";
    success.value = false;

    try {
        const payload = {
            items: cart.items.map((i) => ({
                article_id: i.article.id,
                quantity: i.quantity,
            })),
            shipping_name: form.value.shipping_name,
            shipping_address: form.value.shipping_address,
            shipping_city: form.value.shipping_city,
            shipping_province: form.value.shipping_province,
            shipping_postal_code: form.value.shipping_postal_code,
            shipping_phone: form.value.shipping_phone,
            billing_same_as_shipping: sameAsShipping.value,
            billing_name: form.value.billing_name || null,
            billing_address: form.value.billing_address || null,
            billing_city: form.value.billing_city || null,
            billing_province: form.value.billing_province || null,
            billing_postal_code: form.value.billing_postal_code || null,
            billing_phone: form.value.billing_phone || null,
            card_number: form.value.card_number.replace(/\s/g, ""),
            card_expiry: form.value.card_expiry,
            card_cvv: form.value.card_cvv,
        };

        const response = await api.post("/checkout", payload);
        orderId.value = response.data.order.id;
        success.value = true;
        cart.clear();

        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (e: any) {
        error.value =
            e.response?.data?.message || "Error al procesar el pago";
    } finally {
        loading.value = false;
    }
}

const groupedItems = computed(() => cart.items);
</script>

<template>
    <div class="bg-fondo min-h-screen p-6 md:p-10">
        <div class="mx-auto max-w-5xl space-y-8">
            <div v-if="success" class="text-center md:text-left">
                <div class="bg-card border-borde rounded-3xl border p-8 shadow-sm text-center">
                    <i class="pi pi-check-circle text-verde text-6xl mb-4"></i>
                    <h1 class="text-rojo-fuerte text-3xl font-bold mb-2">¡Pedido realizado!</h1>
                    <p class="text-texto-secundario mb-4">
                        Tu pedido #{{ orderId }} ha sido procesado correctamente.
                    </p>
                    <RouterLink
                        to="/account/history"
                        class="bg-azul hover:bg-azul/90 inline-block rounded-2xl px-6 py-3 font-semibold text-white transition"
                    >
                        Ver historial
                    </RouterLink>
                </div>
            </div>

            <template v-else>
                <div class="text-center md:text-left">
                    <h1 class="text-rojo-fuerte text-4xl font-bold">Checkout</h1>
                    <p class="text-texto-secundario mt-1">
                        Rellena tus datos para finalizar la compra
                    </p>
                </div>

                <div v-if="error" class="bg-rojo-claro/10 border border-red-300 rounded-2xl p-4 text-red-700 text-sm">
                    {{ error }}
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    <div class="space-y-6 md:col-span-2">
                        <!-- SHIPPING -->
                        <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
                            <h2 class="text-rojo-fuerte mb-4 text-lg font-semibold">
                                Dirección de envío
                            </h2>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="text-texto mb-1 block text-sm font-medium">Nombre completo</label>
                                    <input v-model="form.shipping_name" placeholder="María García"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.shipping_name }" />
                                    <p v-if="fieldErrors.shipping_name" class="text-red-500 text-xs mt-1">{{ fieldErrors.shipping_name }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="text-texto mb-1 block text-sm font-medium">Dirección</label>
                                    <input v-model="form.shipping_address" placeholder="Calle Mayor 123"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.shipping_address }" />
                                    <p v-if="fieldErrors.shipping_address" class="text-red-500 text-xs mt-1">{{ fieldErrors.shipping_address }}</p>
                                </div>

                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Ciudad</label>
                                    <input v-model="form.shipping_city" placeholder="Madrid"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.shipping_city }" />
                                    <p v-if="fieldErrors.shipping_city" class="text-red-500 text-xs mt-1">{{ fieldErrors.shipping_city }}</p>
                                </div>

                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Provincia</label>
                                    <input v-model="form.shipping_province" placeholder="Madrid"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.shipping_province }" />
                                    <p v-if="fieldErrors.shipping_province" class="text-red-500 text-xs mt-1">{{ fieldErrors.shipping_province }}</p>
                                </div>

                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Código postal</label>
                                    <input v-model="form.shipping_postal_code" placeholder="28001"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.shipping_postal_code }" />
                                    <p v-if="fieldErrors.shipping_postal_code" class="text-red-500 text-xs mt-1">{{ fieldErrors.shipping_postal_code }}</p>
                                </div>

                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Teléfono</label>
                                    <input v-model="form.shipping_phone" placeholder="612345678"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.shipping_phone }" />
                                    <p v-if="fieldErrors.shipping_phone" class="text-red-500 text-xs mt-1">{{ fieldErrors.shipping_phone }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- BILLING -->
                        <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-rojo-fuerte text-lg font-semibold">
                                    Dirección de facturación
                                </h2>
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" v-model="sameAsShipping"
                                        class="h-4 w-4 rounded border-[var(--color-borde)] text-[var(--color-azul)]" />
                                    <span class="text-texto-secundario">Igual que envío</span>
                                </label>
                            </div>

                            <div v-if="!sameAsShipping" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="text-texto mb-1 block text-sm font-medium">Nombre completo</label>
                                    <input v-model="form.billing_name" placeholder="María García"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.billing_name }" />
                                    <p v-if="fieldErrors.billing_name" class="text-red-500 text-xs mt-1">{{ fieldErrors.billing_name }}</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="text-texto mb-1 block text-sm font-medium">Dirección</label>
                                    <input v-model="form.billing_address" placeholder="Calle Mayor 123"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.billing_address }" />
                                    <p v-if="fieldErrors.billing_address" class="text-red-500 text-xs mt-1">{{ fieldErrors.billing_address }}</p>
                                </div>
                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Ciudad</label>
                                    <input v-model="form.billing_city" placeholder="Madrid"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.billing_city }" />
                                    <p v-if="fieldErrors.billing_city" class="text-red-500 text-xs mt-1">{{ fieldErrors.billing_city }}</p>
                                </div>
                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Provincia</label>
                                    <input v-model="form.billing_province" placeholder="Madrid"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.billing_province }" />
                                    <p v-if="fieldErrors.billing_province" class="text-red-500 text-xs mt-1">{{ fieldErrors.billing_province }}</p>
                                </div>
                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Código postal</label>
                                    <input v-model="form.billing_postal_code" placeholder="28001"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.billing_postal_code }" />
                                    <p v-if="fieldErrors.billing_postal_code" class="text-red-500 text-xs mt-1">{{ fieldErrors.billing_postal_code }}</p>
                                </div>
                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Teléfono</label>
                                    <input v-model="form.billing_phone" placeholder="612345678"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.billing_phone }" />
                                    <p v-if="fieldErrors.billing_phone" class="text-red-500 text-xs mt-1">{{ fieldErrors.billing_phone }}</p>
                                </div>
                            </div>
                            <p v-else class="text-texto-secundario text-sm italic">
                                Se usará la misma dirección de envío para la facturación.
                            </p>
                        </div>

                        <!-- CARD -->
                        <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
                            <h2 class="text-rojo-fuerte mb-4 text-lg font-semibold">
                                Tarjeta de crédito
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-texto mb-1 block text-sm font-medium">Número de tarjeta</label>
                                    <input v-model="form.card_number" @input="formatCardNumber" placeholder="1234 5678 9012 3456" maxlength="19"
                                        class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                        :class="{ 'border-red-400!': fieldErrors.card_number }" />
                                    <p v-if="fieldErrors.card_number" class="text-red-500 text-xs mt-1">{{ fieldErrors.card_number }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-texto mb-1 block text-sm font-medium">Caducidad</label>
                                        <input v-model="form.card_expiry" @input="formatExpiry" placeholder="MM/AA" maxlength="5"
                                            class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                            :class="{ 'border-red-400!': fieldErrors.card_expiry }" />
                                        <p v-if="fieldErrors.card_expiry" class="text-red-500 text-xs mt-1">{{ fieldErrors.card_expiry }}</p>
                                    </div>
                                    <div>
                                        <label class="text-texto mb-1 block text-sm font-medium">CVV</label>
                                        <input v-model="form.card_cvv" placeholder="123" maxlength="3"
                                            class="appearance-none rounded-xl border border-[var(--color-borde)] bg-[var(--color-card)] px-3 py-2 w-full outline-hidden transition-colors duration-200 enabled:hover:border-[var(--color-azul)] enabled:focus:border-[var(--color-azul)] placeholder:text-[var(--color-texto-secundario)]"
                                            :class="{ 'border-red-400!': fieldErrors.card_cvv }" />
                                        <p v-if="fieldErrors.card_cvv" class="text-red-500 text-xs mt-1">{{ fieldErrors.card_cvv }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ITEMS -->
                        <div class="bg-card border-borde rounded-3xl border p-6 shadow-sm">
                            <h2 class="text-rojo-fuerte mb-4 text-lg font-semibold">
                                Productos
                            </h2>

                            <div v-if="cart.items.length > 0" class="space-y-4">
                                <div v-for="item in groupedItems" :key="item.article.id"
                                    class="border-borde flex items-center gap-4 rounded-2xl border bg-white p-4 transition hover:shadow-md">
                                    <div class="border-borde bg-fondo h-20 w-20 shrink-0 overflow-hidden rounded-xl border">
                                        <img :src="Array.isArray(item.article.images) ? (item.article.images[0] ?? '/images/placeholder.jpg') : (item.article.images ?? '/images/placeholder.jpg')"
                                            :alt="item.article.name" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex flex-1 flex-col justify-between gap-1">
                                        <div>
                                            <p class="text-texto font-semibold">{{ item.article.name }}</p>
                                            <p class="text-texto-secundario text-sm">Cantidad: {{ item.quantity }}</p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-texto-secundario text-xs">
                                                {{ item.article.onSale ? "Oferta aplicada" : "Precio estándar" }}
                                            </span>
                                            <span class="text-azul text-lg font-bold">
                                                {{ (item.article.price * item.quantity).toFixed(2) }} €
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: SUMMARY -->
                    <div class="space-y-6">
                        <div class="bg-card border-borde sticky top-6 rounded-3xl border p-6 shadow-sm">
                            <h2 class="text-rojo-fuerte mb-4 text-lg font-semibold">Resumen</h2>

                            <div class="space-y-3 text-sm">
                                <div class="text-texto-secundario flex justify-between">
                                    <span>Productos</span>
                                    <span>{{ cart.items.length }}</span>
                                </div>
                                <div class="text-texto-secundario flex justify-between">
                                    <span>Envío</span>
                                    <span class="text-naranja font-semibold">Gratis</span>
                                </div>
                                <div class="border-borde flex justify-between border-t pt-3 text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-azul">{{ cart.total.toFixed(2) }} €</span>
                                </div>
                            </div>

                            <button @click="pay" :disabled="loading"
                                class="bg-azul hover:bg-azul/90 mt-6 w-full rounded-2xl py-3 font-semibold text-white transition active:scale-[0.99] disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <i v-if="loading" class="pi pi-spinner pi-spin"></i>
                                {{ loading ? "Procesando..." : "Pagar ahora" }}
                            </button>

                            <p class="text-texto-secundario mt-3 text-center text-xs">
                                Pago simulado · no se realiza ninguna transacción real
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

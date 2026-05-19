import { defineStore } from "pinia";
import { ref, computed, watch } from "vue";
import type { Article } from "@/types";

export type CartItem = {
    article: Article;
    quantity: number;
};

export const useCartStore = defineStore("cart", () => {
    const items = ref<CartItem[]>(load());

    // UI STATE (drawer open/close)
    const open = ref(false);

    function openCart() {
        open.value = true;
    }

    function closeCart() {
        open.value = false;
    }

    function toggleCart() {
        open.value = !open.value;
    }

    function load(): CartItem[] {
        const data = localStorage.getItem("cart");
        return data ? JSON.parse(data) : [];
    }

    function persist() {
        localStorage.setItem("cart", JSON.stringify(items.value));
    }

    watch(items, persist, { deep: true });

    function add(article: Article) {
        const found = items.value.find((i) => i.article.id === article.id);

        if (found) {
            if ((found.quantity ?? 0) < (article.stock ?? 999)) {
                found.quantity++;
            }
        } else {
            items.value.push({ article, quantity: 1 });
        }
    }

    function remove(articleId: number) {
        items.value = items.value.filter((i) => i.article.id !== articleId);
    }

    function decrease(articleId: number) {
        const item = items.value.find((i) => i.article.id === articleId);
        if (!item) return;

        item.quantity--;

        if (item.quantity <= 0) {
            remove(articleId);
        }
    }

    function setQuantity(articleId: number, qty: number) {
        const item = items.value.find((i) => i.article.id === articleId);
        if (!item) return;

        const max = item.article.stock ?? 999;

        item.quantity = Math.min(Math.max(qty, 1), max);
    }

    const total = computed(() =>
        items.value.reduce((acc, i) => acc + i.article.price * i.quantity, 0),
    );

    const count = computed(() =>
        items.value.reduce((acc, i) => acc + i.quantity, 0),
    );

    function clear() {
        items.value = [];
    }

    function increase(articleId: number) {
        const item = items.value.find((i) => i.article.id === articleId);
        if (!item) return;

        const max = item.article.stock ?? 999;

        if (item.quantity < max) {
            item.quantity++;
        }
    }

    return {
        items,
        total,
        count,

        // actions carrito
        add,
        remove,
        decrease,
        increase,
        setQuantity,
        clear,

        // UI drawer
        open,
        openCart,
        closeCart,
        toggleCart,
    };
});

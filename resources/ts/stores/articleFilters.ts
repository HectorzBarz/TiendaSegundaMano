import { defineStore } from "pinia";

export const useArticleFiltersStore = defineStore("articleFilters", {
    state: () => ({
        categoryId: 0,
        articleName: "",
        minPrice: 0,
        maxPrice: Infinity,
        onSale: false,
    }),

    actions: {
        setFilters(payload: any) {
            this.categoryId = payload.categoryId ?? 0;
            this.articleName = payload.articleName ?? "";
            this.minPrice = payload.minPrice ?? 0;
            this.maxPrice = payload.maxPrice ?? Infinity;
            this.onSale = payload.onSale ?? false;
        },

        reset() {
            this.categoryId = 0;
            this.articleName = "";
            this.minPrice = 0;
            this.maxPrice = Infinity;
            this.onSale = false;
        },
    },
});

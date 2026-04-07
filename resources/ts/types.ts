export type Article = {
    id: number;
    name: string;
    oldPrice?: number;
    price: number;
    onSale: Boolean;
    img?: string;
    categoryId: number;
};

export type Category = {
    id: number;
    name: string;
    img?: string;
};

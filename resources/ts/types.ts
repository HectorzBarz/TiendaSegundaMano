export type Article = {
    id: number;
    name: string;
    description?: string;
    oldPrice?: number;
    price: number;
    onSale: boolean;
    img?: string;
    itemState?: number;
    categoryId: number;
    stock?: number;
    product_avg?: number;
    product_count?: number;
    sell_count?: number;
};

export type Category = {
    id: number;
    name: string;
    image?: string;
};

export type User = {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    phone?: string;
};

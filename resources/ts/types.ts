export type ArticleCard = {
    id: number;
    name: string;
    oldPrice?: number;
    price: number;
    onSale: Boolean;
    img?: string;
    categoryId: number;
};

export type Article = {
    id: number;
    name: string;
    description?: string;
    oldPrice?: number;
    price: number;
    onSale: Boolean;
    img?: string;
    itemState?: number;
    categoryId: number;
    stock?: number;
};

export type Category = {
    id: number;
    name: string;
    img?: string;
};

// Lo que usa tu aplicación (UI)
export interface Article {
    id: number;
    name: string;
    description?: string;
    oldPrice?: number;
    price: number;
    onSale: boolean;
    img?: string; // Propiedad calculada para la miniatura
    itemState?: number;
    categoryId: number;
    stock?: number;
    product_avg?: number;
    product_count?: number;
    sell_count?: number;
    images?: string[] | string; // Mantenemos el origen por si se necesita en detalles
}

// Lo que recibes exactamente del Backend (Laravel)
export interface RawArticle extends Omit<
    Article,
    "img" | "onSale" | "oldPrice"
> {
    // Laravel suele usar snake_case y el campo images puede variar
    images: string[] | string;
    on_sale?: boolean | number;
    old_price?: number;
    // Añade aquí cualquier otro campo que venga en snake_case desde el server
}

// Estructura de la respuesta de la API
export interface ApiResponse<T> {
    data: T;
    // Puedes añadir meta, links, etc. si usas paginación de Laravel
}

export type Category = {
    id: number;
    name: string;
    image?: string;
};

export interface User {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    phone?: string | null;
    birth_date?: string | null;
    city?: string | null;
    postal_code?: string | null;
    shipping_address?: string | null;
    billing_address?: string | null;
    profile_image?: string | null;
}

export type SelectOption = {
    label: string;
    value: number;
};

export interface OrderItem {
    id: number;
    order_id: number;
    article_id: number;
    article_name: string;
    quantity: number;
    unit_price: number;
    subtotal: number;
    created_at: string;
    updated_at: string;
}

export interface Order {
    id: number;
    user_id: number;
    status: 'pending' | 'processing' | 'completed' | 'delivered' | 'cancelled';
    total: number;
    shipping_name: string;
    shipping_address: string;
    shipping_city: string;
    shipping_province: string;
    shipping_postal_code: string;
    shipping_phone: string;
    billing_same_as_shipping: boolean;
    billing_name: string | null;
    billing_address: string | null;
    billing_city: string | null;
    billing_province: string | null;
    billing_postal_code: string | null;
    billing_phone: string | null;
    card_last_four: string | null;
    card_brand: string | null;
    created_at: string;
    updated_at: string;
    items: OrderItem[];
    user?: User;
}

export interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    address?: string;
    city?: string;
    province?: string;
    postal_code?: string;
    avatar?: string;
    roles: string[];
}

export interface Category {
    id: number;
    name: string | Record<string, string>;
    description?: string | Record<string, string>;
    slug: string;
    parent_id?: number;
    sort_order: number;
    is_active: boolean;
    products_count?: number;
    children?: Category[];
    parent?: Category;
}

export interface Product {
    id: number;
    name: string | Record<string, string>;
    description?: string | Record<string, string>;
    short_description?: string | Record<string, string>;
    slug: string;
    sku: string;
    price: number;
    compare_price?: number;
    cost_price?: number;
    category_id?: number;
    is_active: boolean;
    is_featured: boolean;
    weight?: number;
    category?: Category;
    variants?: ProductVariant[];
    media?: Media[];
}

export interface ProductVariant {
    id: number;
    product_id: number;
    name: string | Record<string, string>;
    sku: string;
    price?: number;
    stock: number;
    low_stock_threshold: number;
    attributes?: Record<string, string>;
    is_active: boolean;
    effective_price?: number;
}

export interface Order {
    id: number;
    order_number: string;
    user_id?: number;
    status: string;
    payment_status: string;
    payment_method?: string;
    payment_gateway?: string;
    payment_reference?: string;
    subtotal: number;
    discount_amount: number;
    shipping_cost: number;
    tax_amount: number;
    total: number;
    shipping_name: string;
    shipping_phone: string;
    shipping_address: string;
    shipping_city: string;
    shipping_province: string;
    shipping_postal_code: string;
    notes?: string;
    promo_code?: string;
    items?: OrderItem[];
    user?: User;
    created_at: string;
    paid_at?: string;
    shipped_at?: string;
    delivered_at?: string;
}

export interface OrderItem {
    id: number;
    order_id: number;
    product_id: number;
    product_variant_id?: number;
    product_name: string;
    variant_name?: string;
    sku: string;
    price: number;
    quantity: number;
    subtotal: number;
}

export interface Cart {
    id: number;
    user_id?: number;
    session_id?: string;
    promo_code?: string;
    items: CartItem[];
    subtotal: number;
}

export interface CartItem {
    id: number;
    cart_id: number;
    product_id: number;
    product_variant_id?: number;
    quantity: number;
    product: Product;
    productVariant?: ProductVariant;
}

export interface Promo {
    id: number;
    code: string;
    name: string | Record<string, string>;
    description?: string | Record<string, string>;
    type: 'percentage' | 'fixed';
    value: number;
    minimum_order?: number;
    maximum_discount?: number;
    usage_limit?: number;
    usage_count: number;
    per_user_limit?: number;
    starts_at?: string;
    expires_at?: string;
    is_active: boolean;
}

export interface Setting {
    id: number;
    group: string;
    key: string;
    value: any;
    type: string;
}

export interface PaymentGateway {
    id: number;
    name: string;
    display_name: string;
    config: Record<string, any>;
    is_active: boolean;
    is_sandbox: boolean;
    sort_order: number;
}

export interface EmailTemplate {
    id: number;
    name: string;
    slug: string;
    subject: Record<string, string>;
    body: Record<string, string>;
    variables?: string[];
    is_active: boolean;
}

export interface Page {
    id: number;
    title: string | Record<string, string>;
    slug: string;
    content: string | Record<string, string>;
    meta_title?: string | Record<string, string>;
    meta_description?: string | Record<string, string>;
    is_active: boolean;
}

export interface StockMovement {
    id: number;
    product_variant_id: number;
    quantity: number;
    type: string;
    reference?: string;
    notes?: string;
    user_id?: number;
    productVariant?: ProductVariant;
    user?: User;
    created_at: string;
}

export interface Media {
    id: number;
    original_url: string;
    preview_url?: string;
    name: string;
    file_name: string;
    size: number;
    mime_type: string;
}

export interface PaginatedData<T> {
    data: T[];
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
}

export interface FlashMessages {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
}

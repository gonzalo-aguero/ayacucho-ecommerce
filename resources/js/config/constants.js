// Global configuration constants

/**
 * @global {boolean} DEBUG - Inyectado por Laravel Blade
 * @global {boolean} IS_PRODUCT_PAGE - Inyectado por Laravel Blade
 */

// API endpoints
export const API_ENDPOINTS = {
    GOOGLE_REVIEWS: '/api/google-reviews',
    PRODUCTS: '/api/products',
    PAYMENT_METHODS: '/api/payment-methods',
    SHIPPING_ZONES: '/api/shipping-zones',
    VARIATIONS: '/api/variations'
};

// Base URL
export const BASE_URL = window.location.origin;

// Notification settings
export const NOTIFICATION_DURATIONS = {
    SUCCESS: 1500,
    WARNING: 3500,
    ERROR: 3500
};

// Cart settings
export const CART_COOKIE_EXPIRATION_DAYS = 21;

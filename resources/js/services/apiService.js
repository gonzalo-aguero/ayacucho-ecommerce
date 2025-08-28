import { API_ENDPOINTS, BASE_URL } from '../config/constants';
import { handleError, AppError } from '../utils/errorHandler';

export class ApiService {
    static async fetch(endpoint, options = {}) {
        try {
            const url = `${BASE_URL}${endpoint}`;
            const response = await fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                ...options
            });

            if (!response.ok) {
                throw new AppError(
                    `HTTP ${response.status}: ${response.statusText}`,
                    'HTTP_ERROR',
                    { status: response.status, url }
                );
            }

            return await response.json();
        } catch (error) {
            if (error instanceof AppError) {
                throw error;
            }
            throw new AppError(
                `Failed to fetch from ${endpoint}`,
                'FETCH_ERROR',
                { originalError: error.message, endpoint }
            );
        }
    }

    static async get(endpoint) {
        return this.fetch(endpoint);
    }

    static async post(endpoint, data) {
        return this.fetch(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }
}

// Specific API methods
export const api = {
    getGoogleReviews: () => ApiService.get(API_ENDPOINTS.GOOGLE_REVIEWS),
    getProducts: () => ApiService.get(API_ENDPOINTS.PRODUCTS),
    getPaymentMethods: () => ApiService.get(API_ENDPOINTS.PAYMENT_METHODS),
    getShippingZones: () => ApiService.get(API_ENDPOINTS.SHIPPING_ZONES),
    getVariations: () => ApiService.get(API_ENDPOINTS.VARIATIONS)
};

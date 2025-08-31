"use strict";
import { api } from './apiService';
import { handleError, logInfo } from '../utils/errorHandler';

export class PaymentMethodService {
    constructor(methods = []) {
        this._validateMethods(methods);
        this._methods = methods;
        this._selectedPaymentMethod = undefined;
    }

    /**
     * Validates payment methods array
     * @param {Array} methods - Payment methods array
     * @private
     */
    _validateMethods(methods) {
        if (!Array.isArray(methods)) {
            throw new Error('Methods must be an array');
        }

        methods.forEach((method, index) => {
            if (!method || typeof method !== 'object') {
                throw new Error(`Invalid method at index ${index}`);
            }
            if (typeof method.name !== 'string' || method.name.trim() === '') {
                throw new Error(`Method at index ${index} must have a valid name`);
            }
            if (typeof method.percent !== 'number') {
                throw new Error(`Method at index ${index} must have a valid percent`);
            }
        });
    }

    /**
     * Gets the methods array
     * @returns {Array} Methods array
     */
    get methods() {
        return this._methods;
    }

    /**
     * Sets the methods array
     * @param {Array} methods - Methods array
     */
    set methods(methods) {
        this._validateMethods(methods);
        this._methods = methods; // Store copy
    }

    get selectedPaymentMethod() {
        return this._selectedPaymentMethod;
    }
    set selectedPaymentMethod(method) {
        if (method === undefined || method === null) {
            this._selectedPaymentMethod = undefined;
            return;
        }
        const found = this._methods.find(m => m.name === method.name);
        if (found) {
            this._selectedPaymentMethod = { ...found }; // Store copy
        } else {
            throw new Error('Selected method is not in the methods list');
        }
    }

    /**
     * Loads payment methods from the API
     * @returns {Promise<boolean>} Success status
     */
    async load() {
        try {
            const data = await api.getPaymentMethods();

            if (!Array.isArray(data)) {
                throw new Error('Invalid payment methods data format');
            }

            this._methods = data;
            logInfo(`Loaded ${this._methods.length} payment methods`, 'PaymentMethods.load');
            return true;
        } catch (error) {
            handleError(error, 'PaymentMethods.load');
            return false;
        }
    }

    /**
     * Gets a payment method by index
     * @param {number} index - Method index
     * @returns {Object|null} Payment method or null if invalid index
     */
    getByIndex(index) {
        if (index >= 0 && index < this._methods.length) {
            return { ...this._methods[index] }; // Return copy
        }
        return null;
    }

    /**
     * Gets a payment method by name
     * @param {string} name - Method name
     * @returns {Object|null} Payment method or null if not found
     */
    getByName(name) {
        if (typeof name !== 'string') {
            return null;
        }

        const method = this._methods.find(m => m.name.toLowerCase() === name.toLowerCase());
        return method ? { ...method } : null; // Return copy
    }

    /**
     * Gets the number of payment methods
     * @returns {number} Number of methods
     */
    getCount() {
        return this._methods.length;
    }

    /**
     * Checks if methods are loaded
     * @returns {boolean} True if methods are loaded
     */
    isLoaded() {
        return this._methods.length > 0;
    }

    /**
     * Returns a formatted text with the name and percent of the payment method
     * @param {Object} method - Payment method object
     * @returns {string} Formatted text
     */
    getText(method) {
        try {
            if (!method || typeof method !== 'object') {
                throw new Error('Invalid method parameter');
            }

            const { name, percent } = method;

            if (typeof name !== 'string' || typeof percent !== 'number') {
                throw new Error('Method must have name and percent properties');
            }

            if (percent === 0) {
                return name;
            } else if (percent < 0) {
                return `${name} (${Math.abs(percent)}% de descuento)`;
            } else {
                return `${name} (${percent}% de recargo)`;
            }
        } catch (error) {
            handleError(error, 'PaymentMethods.getText');
            return 'Método de pago inválido';
        }
    }

    /**
     * Returns all formatted texts with the name and percent of each payment method
     * @returns {Array} Array of formatted texts
     */
    getTexts() {
        try {
            return this._methods.map((method, index) => ({
                index,
                name: this.getText(method),
                originalName: method.name,
                percent: method.percent
            }));
        } catch (error) {
            handleError(error, 'PaymentMethods.getTexts');
            return [];
        }
    }

    /**
     * Returns the value of the price with the discount or surcharge percentage
     * @param {number} value - Original value
     * @param {Object} method - Payment method object
     * @returns {number} Final value
     */
    applyPercent(value, method) {
        try {
            if (typeof value !== 'number' || isNaN(value)) {
                throw new Error('Value must be a valid number');
            }

            if (!method || typeof method !== 'object') {
                throw new Error('Invalid method parameter');
            }

            const { percent } = method;
            if (typeof percent !== 'number' || isNaN(percent)) {
                throw new Error('Method must have a valid percent property');
            }

            if (percent === 0) {
                return value;
            }

            // The negative percentage already has the minus sign
            const finalValue = value * (1 + percent / 100);
            return Math.round(finalValue * 100) / 100; // Round to 2 decimal places
        } catch (error) {
            handleError(error, 'PaymentMethods.applyPercent');
            return value; // Return original value on error
        }
    }

    /**
     * Gets methods with zero percent (no fee)
     * @returns {Array} Methods with no fee
     */
    getFreeMethods() {
        return this._methods
            .filter(method => method.percent === 0)
            .map(method => ({ ...method }));
    }

    /**
     * Gets methods with discount (negative percent)
     * @returns {Array} Methods with discount
     */
    getDiscountMethods() {
        return this._methods
            .filter(method => method.percent < 0)
            .map(method => ({ ...method }));
    }

    /**
     * Gets methods with surcharge (positive percent)
     * @returns {Array} Methods with surcharge
     */
    getSurchargeMethods() {
        return this._methods
            .filter(method => method.percent > 0)
            .map(method => ({ ...method }));
    }
}

export default PaymentMethodService;

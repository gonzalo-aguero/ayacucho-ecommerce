"use strict";
import { handleError, logWarning } from '../utils/errorHandler';

export class Order {
    constructor(cart, paymentMethods, shippingZones) {
        this.cart = cart;
        this.paymentMethods = paymentMethods;
        this.shippingZones = shippingZones;
        this.selectedPaymentMethod = null;
        this.selectedShippingZone = null;
    }

    /**
     * Sets the selected payment method
     * @param {Object} paymentMethod - Payment method object
     */
    setPaymentMethod(paymentMethod) {
        if (!paymentMethod || typeof paymentMethod !== 'object') {
            throw new Error('Invalid payment method');
        }
        this.selectedPaymentMethod = paymentMethod;
    }

    /**
     * Sets the selected shipping zone
     * @param {Object} shippingZone - Shipping zone object
     */
    setShippingZone(shippingZone) {
        if (!shippingZone || typeof shippingZone !== 'object') {
            throw new Error('Invalid shipping zone');
        }
        this.selectedShippingZone = shippingZone;
    }

    /**
     * Validates if the order is ready for calculation
     * @returns {Object} Validation result with status and errors
     */
    validate() {
        const errors = [];

        if (!this.cart || this.cart.length() === 0) {
            errors.push('El carrito está vacío');
        }

        if (!this.selectedShippingZone) {
            errors.push('Falta seleccionar la zona de envío');
        }

        if (!this.selectedPaymentMethod) {
            errors.push('Falta seleccionar el método de pago');
        }

        return {
            isValid: errors.length === 0,
            errors
        };
    }

    /**
     * Calculates the order total
     * @param {Function} getProductPrice - Function to get product price by ID
     * @returns {number} Order total
     */
    total(getProductPrice) {
        try {
            const validation = this.validate();
            if (!validation.isValid) {
                validation.errors.forEach(error => logWarning(error, 'Order.total'));
                return 0;
            }

            let orderTotal = 0;

            // Calculate cart total
            const cartTotal = this.cart.total(getProductPrice);
            if (cartTotal === 0) {
                logWarning('Cart total is 0', 'Order.total');
                return 0;
            }

            // Add shipping cost
            if (this.selectedShippingZone && this.selectedShippingZone.cost !== undefined) {
                orderTotal += parseFloat(this.selectedShippingZone.cost);
            }

            // Apply payment method percentage
            let finalCartTotal = cartTotal;
            if (this.selectedPaymentMethod && this.paymentMethods) {
                finalCartTotal = this.paymentMethods.applyPercent(cartTotal, this.selectedPaymentMethod);
            }

            orderTotal += finalCartTotal;

            return Math.round(orderTotal * 100) / 100; // Round to 2 decimal places
        } catch (error) {
            handleError(error, 'Order.total');
            return 0;
        }
    }

    /**
     * Gets order summary
     * @param {Function} getProductPrice - Function to get product price by ID
     * @returns {Object} Order summary
     */
    getSummary(getProductPrice) {
        try {
            const cartTotal = this.cart.total(getProductPrice);
            const shippingCost = this.selectedShippingZone ? parseFloat(this.selectedShippingZone.cost) : 0;
            const paymentFee = this.selectedPaymentMethod && this.paymentMethods
                ? this.paymentMethods.applyPercent(cartTotal, this.selectedPaymentMethod) - cartTotal
                : 0;
            const total = this.total(getProductPrice);

            return {
                cartTotal: Math.round(cartTotal * 100) / 100,
                shippingCost: Math.round(shippingCost * 100) / 100,
                paymentFee: Math.round(paymentFee * 100) / 100,
                total: Math.round(total * 100) / 100,
                items: this.cart.length(),
                paymentMethod: this.selectedPaymentMethod ? this.selectedPaymentMethod.name : null,
                shippingZone: this.selectedShippingZone ? this.selectedShippingZone.name : null
            };
        } catch (error) {
            handleError(error, 'Order.getSummary');
            return null;
        }
    }

    /**
     * Checks if order can be processed
     * @returns {boolean} True if order is ready
     */
    canProcess() {
        const validation = this.validate();
        return validation.isValid;
    }

    /**
     * Resets the order
     */
    reset() {
        this.selectedPaymentMethod = null;
        this.selectedShippingZone = null;
    }
}

export default Order;

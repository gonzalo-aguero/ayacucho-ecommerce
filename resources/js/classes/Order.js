"use strict";
import { logInfo, logWarning } from '../utils/error';

export class Order {
    constructor(productService, paymentMethodService, shippingZoneService) {
        this._productService = productService;
        this._paymentMethodService = paymentMethodService;
        this._shippingZoneService = shippingZoneService;
    }

    /**
     * Calculates the order total including products, shipping and payment fees
     * @returns {number} Order total
     */
    total() {
        // Calculate cart total
        const cartTotal = this._productService.cartTotal();
        if (cartTotal === 0) {
            logWarning('Cart total is 0', 'Order.total');
            return 0;
        }

        // Add shipping cost
        const selectedShippingZone = this._shippingZoneService.selectedShippingZone;
        let shippingCost = 0;
        if (selectedShippingZone && selectedShippingZone.cost !== undefined) {
            shippingCost = parseFloat(selectedShippingZone.cost);
        }

        // Apply payment method percentage
        const selectedPaymentMethod = this._paymentMethodService.selectedPaymentMethod;
        let cartTotalWithPaymentMethod = cartTotal;
        if (selectedPaymentMethod && selectedPaymentMethod.percent !== undefined) {
            cartTotalWithPaymentMethod = this._paymentMethodService.applyPercent(cartTotal, selectedPaymentMethod);
        }

        let orderTotal = cartTotalWithPaymentMethod + shippingCost;

        logInfo(
            {
                orderTotal,
                cartTotal,
                shippingCost,
                cartTotalWithPaymentMethod
            },'Order.total');
        logInfo({ selectedPaymentMethod },'Order.total');
        logInfo({ selectedShippingZone },'Order.total');

        return Math.round(orderTotal * 100) / 100; // Round to 2 decimal places
    }
}

export default Order;

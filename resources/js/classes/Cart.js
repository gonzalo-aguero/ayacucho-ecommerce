"use strict";
import { CART_COOKIE_EXPIRATION_DAYS } from '../config/constants';
import { handleError, logWarning, logInfo } from '../utils/error';

export class Cart {
    constructor(store) {
        this._content = []; // [{item, units, ?option}, {item, units, ?option}]
        this.load();
    }

    /**
     * Adds a product to the cart
     * @param {Object} productData - Product data object
     * @param {number} units - Number of units to add
     * @param {string} optionValue - Optional variation value
     * @returns {boolean} Success status
     */
    add(productData, units = 1, optionValue, position) {

        try {
            if (!productData || !productData.id) {
                throw new Error('Invalid product data');
            }

            units = parseInt(units);
            if (units <= 0) {
                throw new Error('Units must be greater than 0');
            }

            if(position === undefined || typeof position !== 'number' || position < 0){
                throw new Error('Product position in products array is required and must be a non-negative number');
            }

            // Check if item with same productId and option already exists
            const cartItem = this.findCartItem(productData.id, optionValue);

            if (cartItem) {
                cartItem.units += units;
            } else {
                this.content.push({
                    productId: productData.id,
                    units,
                    option: optionValue,
                    pos: position  // position in products array (for direct access without searching)
                });
            }

            this.save();
            logInfo(this.content, "Cart.add");
            return true;
        } catch (error) {
            handleError(error, 'Cart.add');
            return false;
        }
    }

    /**
     * Finds a cart item by product ID and option
     * @param {string} productId - Product ID
     * @param {string} optionValue - Optional variation value
     * @returns {Object|null} Cart item or null if not found
     */
    findCartItem(productId, optionValue) {
        let cartItem = null;
        if(optionValue === undefined){
            cartItem = this.content.find(item => item.productId === productId);
        }else{
            cartItem = this.content.find(item => item.productId === productId && item.option === optionValue);
        }
        return cartItem;
    }

    /**
     * Gets the content array
     * @returns {Array} Cart content
     */
    get content() {
        return this._content;
    }
    set content(value) {
        this._content = Array.isArray(value) ? value : [];
    }

    /**
     * Gets units for a specific product
     * @param {string} productId - Product ID
     * @param {string} optionValue - Optional variation value
     * @returns {number} Number of units in cart
     */
    getUnits(productId, optionValue) {
        try {
            const cartItem = this.findCartItem(productId, optionValue);
            return cartItem ? cartItem.units : 0;
        } catch (error) {
            handleError(error, 'Cart.getUnits');
            return 0;
        }
    }

    /**
     * Removes an item from the cart
     * @param {Object} item - Cart item to remove
     * @param {string} optionValue - Optional variation value
     * @returns {boolean} Success status
     */
    remove(item, optionValue) {
        try {
            const hasOption = optionValue !== undefined && optionValue !== '';
            const index = this.content.findIndex(cartItem =>
                cartItem.productId === item.productId &&
                (hasOption ? cartItem.option === optionValue : (cartItem.option === undefined || cartItem.option === ''))
            );

            if (index !== -1) {
                this.content.splice(index, 1);
                this.save();
                return true;
            }
            return false;
        } catch (error) {
            handleError(error, 'Cart.remove');
            return false;
        }
    }

    /**
     * Clears the cart
     */
    clear() {
        this.content = [];
        this.save();
    }

    /**
     * Saves cart to cookies
     */
    save() {
        try {
            const secondsInADay = 60 * 60 * 24;
            const days = CART_COOKIE_EXPIRATION_DAYS * secondsInADay;
            const expirationDate = new Date(new Date().getTime() + days * 1000).toUTCString();

            document.cookie = `cart=${JSON.stringify(this._content)}; expires=${expirationDate}; max-age=${days}; path=/; SameSite=Lax`;
        } catch (error) {
            handleError(error, 'Cart.save');
        }
    }

    /**
     * Loads cart from cookies
     */
    load() {
        try {
            const match = document.cookie.match(/(?:^|;)\s*cart=([^;]*)/);
            if (match && match[1]) {
                const raw = match[1];
                const parsed = JSON.parse(raw);
                this.content = parsed.map(prod => ({
                    productId: prod.productId,
                    units: Math.max(1, parseInt(prod.units)),
                    option: prod.option,
                    pos: prod.pos
                }));
            } else {
                this.save(); // Initialize empty cart
            }
        } catch (error) {
            logWarning('Cart load failed, initializing empty cart', 'Cart.load');
            this.content = [];
            this.save();
        }
    }

    /**
     * Gets the number of items in the cart
     * @returns {number} Number of items
     */
    length() {
        return this.content.length;
    }

    /**
     * Calculates the total price of items in the cart
     * @param {Function} getProductPriceById - Function to get product price id
     * @returns {number} Total price
     */
    total(getProductPriceById) {
        try {
            if (typeof getProductPriceById !== 'function') {
                throw new Error('getProductPriceById function is required');
            }

            let total = 0;
            this.content.forEach(item => {
                const price = getProductPriceById(item.productId);
                if (price !== undefined) {
                    total += price * item.units;
                }
            });

            return total;
        } catch (error) {
            handleError(error, 'Cart.total');
            return 0;
        }
    }
}

export default Cart;

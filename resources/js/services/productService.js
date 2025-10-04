import { BASE_URL } from '../config/constants';
import { handleError } from '../utils/error';

export default class ProductService {

    constructor(cart, notificationService, variationService) {
        if (!cart || typeof cart !== 'object') {
            throw new Error('Valid cart instance is required');
        }

        if (!notificationService || typeof notificationService !== 'object') {
            throw new Error('Notification service is required');
        }
        if (!variationService || typeof variationService.getByValue !== 'function') {
            throw new Error('Valid variations instance is required');
        }

        this._cart = cart;
        this._notificationService = notificationService;
        this._variationService = variationService
        this._products = []; // This will hold the product data
        this._productsById = Object.create(null);
        this._productsToPrint = []; // This will hold the products to print
        this._sortedProducts = []; // This will hold the sorted products by category
        this._printedProductsMax = -1; // This will hold the maximum number of products to print
    }

    /**
     * Returns all loaded products.
     * @returns {Array} Array of product objects
     */
    get products() {
        return this._products;
    }
    set products(products = []) {
        this._products = Array.isArray(products) ? products : [];
        this._productsById = Object.fromEntries(this._products.map(p => [p.id, p])); // Map for quick access by ID
    }

    set sortedProducts(products = []) {
        this._sortedProducts = Array.isArray(products) ? products : [];
    }
    get sortedProducts() {
        return this._sortedProducts;
    }

    set productsToPrint(products = []) {
        this._productsToPrint = Array.isArray(products) ? products : [];
    }
    get productsToPrint() {
        return this._productsToPrint;
    }
    get printedProductsMax() {
        return this._printedProductsMax;
    }
    set printedProductsMax(max) {
        this._printedProductsMax = (typeof max === 'number') ? max : -1;
    }

    /**
     * Calculates the equivalent units in square meters
     * @param {number} units - Number of units
     * @param {Object} productData - Product data object
     * @returns {string} Square meters with 2 decimal places
     */
    squareMeters(units, productData) {
        try {
            const parsedUnits = Number(units);
            if (isNaN(parsedUnits) || parsedUnits < 0) {
                throw new Error('Units must be a number greater than 0');
            }

            if (!productData || typeof productData !== 'object') {
                throw new Error('Product data is required');
            }

            if (!productData.m2ByUnit || typeof productData.m2ByUnit !== 'number') {
                throw new Error('Product must have m2ByUnit property');
            }

            return (units * productData.m2ByUnit).toFixed(2);
        } catch (error) {
            handleError(error, 'ProductService.squareMeters');
            return '0.00';
        }
    }

    /**
     * Generates the product page URL
     * @param {Object} productData - Product data object
     * @returns {string} Product page URL
     */
    getProductPageUrl(productData) {
        try {
            if (!productData || typeof productData !== 'object') {
                throw new Error('Product data is required');
            }

            if (!productData.name || !productData.id) {
                throw new Error('Product must have name and id properties');
            }

            let url = `${BASE_URL}/${productData.name}/${productData.id}`;
            url = url.replace(/ /g, '-').toLowerCase();
            return url;
        } catch (error) {
            handleError(error, 'ProductService.getProductPageUrl');
            return BASE_URL;
        }
    }

    /**
     * Returns TRUE if it's measurable in square meter
     * @param {Object} productData - Product data object
     * @returns {boolean} True if measurable in m²
     */
    measurableInM2(productData) {
        try {
            if (!productData || typeof productData !== 'object') {
                throw new Error('Product data is required');
            }

            return productData.m2Price != null && productData.m2ByUnit != null;
        } catch (error) {
            handleError(error, 'ProductService.measurableInM2');
            return false;
        }
    }

    /**
     * Adds a product to the cart
     * @param {number} units - Number of units to add
     * @param {Object} productData - Product data object
     * @param {string} optionValue - Optional variation value
     * @returns {boolean} Success status
     */
    addToCart(units = 1, productData, optionValue) {
        try {
            if (typeof units !== 'number' || units <= 0) {
                if (this._notificationService) {
                    this._notificationService.invalidQuantity();
                }
                return false;
            }

            if (!productData || typeof productData !== 'object') {
                throw new Error('Product data is required');
            }

            if (this.canBeAdded(units, productData, optionValue)) {
                // find position in products array
                const position = this._products.findIndex(p => p.id === productData.id);
                if (position === -1) {
                    throw new Error('Product not found in products array');
                }

                // Add to cart
                const success = this._cart.add(productData, units, optionValue, position);

                if (success) {
                    this._notificationService.addedToCart();
                } else {
                    this._notificationService.cartError();
                }

                return success;
            } else {
                this._notificationService.insufficientStock();
                return false;
            }
        } catch (error) {
            handleError(error, 'ProductService.addToCart');
            if (this._notificationService) {
                this._notificationService.cartError();
            }
            return false;
        }
    }

    /**
     * Returns True if "units to be added + units already added"
     * do not exceed the available stock
     * @param {number} units - Units to add
     * @param {Object} productData - Product data object
     * @param {string} optionValue - Optional variation value
     * @returns {boolean} True if can be added
     */
    canBeAdded(units, productData, optionValue) {
        try {
            if (!productData || !this._variationService) {
                return false;
            }

            const unitsInCart = this._cart.getUnits(productData.id, optionValue);
            return this.validUnits(unitsInCart + units, productData, optionValue);
        } catch (error) {
            handleError(error, 'ProductService.canBeAdded');
            return false;
        }
    }

    /**
     * This function returns true if the number of units is
     * not greater than the available stock
     * @param {number} units - Total units (cart + new)
     * @param {Object} productData - Product data object
     * @param {string} optionValue - Optional variation value
     * @returns {boolean} True if units are valid
     */
    validUnits(units, productData, optionValue) {
        try {
            if (!productData) {
                return false;
            }

            const u = Number(units);
            if (!Number.isFinite(u) || u <= 0) {
                return false;
            }

            const hasVariation = optionValue && optionValue !== '' && productData.variationId !== null;

            if (this.measurableInM2(productData)) {
                const m2ByUnit = productData.m2ByUnit;

                if (!hasVariation) {
                    return u * m2ByUnit <= productData.units;
                } else {
                    const optionData = this._variationService.getByValue(productData.variationId, optionValue);
                    return optionData && u * m2ByUnit <= optionData.units;
                }
            } else {
                if (!hasVariation) {
                    return u <= productData.units;
                } else {
                    const optionData = this._variationService.getByValue(productData.variationId, optionValue);
                    return optionData && u <= optionData.units;
                }
            }
        } catch (error) {
            handleError(error, 'ProductService.validUnits');
            return false;
        }
    }

    /**
     * Returns the maximum number of units, boxes or packages of a product that can be added to the cart.
     * In the case of being ceramic, it returns the maximum available number
     * of boxes according to the available square meters.
     * @param {Object} productData - Product data object
     * @param {string} optionValue - Optional variation value
     * @returns {number} Maximum available units
     */
    maxAvailableUnits(productData, optionValue=null) {
        try {
            if (!productData) {
                return 0;
            }

            const hasVariation = optionValue && optionValue !== '' && productData.variationId !== null

            if (this.measurableInM2(productData)) {
                if (hasVariation) {
                    const optionData = this._variationService.getByValue(productData.variationId, optionValue);
                    if (optionData && productData.m2ByUnit) {
                        return parseInt(parseFloat(optionData.units) / parseFloat(productData.m2ByUnit));
                    }
                } else if (productData.m2ByUnit) {
                    return parseInt(parseFloat(productData.units) / parseFloat(productData.m2ByUnit));
                }
            } else {
                if (hasVariation) {
                    const optionData = this._variationService.getByValue(productData.variationId, optionValue);
                    return optionData ? optionData.units : 0;
                } else {
                    return productData.units || 0;
                }
            }

            return 0;
        } catch (error) {
            handleError(error, 'ProductService.maxAvailableUnits');
            return 0;
        }
    }

    getById(id) {
        return this._productsById?.[id];
    }

    getPriceById = (id) => {
        const p = this.getById(id);
        return p ? p.price : 0;
    }

    /**
     * Calculates the total price of the cart passing the price retrieval function
     * @returns {number} Total cart price
     */
    cartTotal() {
        return this._cart.total(this.getPriceById);
    }
}

"use strict";

export class Product {
    constructor(data) {
        if (!data || typeof data !== 'object') {
            throw new Error('Product data is required');
        }
        this.data = { ...data }; // Store copy
    }

    /**
     * Gets the product data
     * @returns {Object} Product data copy
     */
    getData() {
        return { ...this.data };
    }

    /**
     * Gets a specific property from product data
     * @param {string} property - Property name
     * @returns {*} Property value or undefined
     */
    get(property) {
        return this.data[property];
    }

    /**
     * Checks if the product has a specific property
     * @param {string} property - Property name
     * @returns {boolean} True if property exists
     */
    has(property) {
        return this.data.hasOwnProperty(property);
    }

    /**
     * Gets the product ID
     * @returns {string} Product ID
     */
    getId() {
        return this.data.id;
    }

    /**
     * Gets the product name
     * @returns {string} Product name
     */
    getName() {
        return this.data.name;
    }

    /**
     * Gets the product price
     * @returns {number} Product price
     */
    getPrice() {
        return this.data.price;
    }

    /**
     * Gets the product units (stock)
     * @returns {number} Product units
     */
    getUnits() {
        return this.data.units;
    }

    /**
     * Gets the product category
     * @returns {string} Product category
     */
    getCategory() {
        return this.data.category;
    }

    /**
     * Gets the product variation ID
     * @returns {number|null} Variation ID or null
     */
    getVariationId() {
        return this.data.variationId || null;
    }

    /**
     * Checks if the product is measurable in square meters
     * @returns {boolean} True if measurable in m²
     */
    isMeasurableInM2() {
        return this.data.m2Price != null && this.data.m2ByUnit != null;
    }

    /**
     * Gets the m² price
     * @returns {number|null} m² price or null
     */
    getM2Price() {
        return this.data.m2Price || null;
    }

    /**
     * Gets the m² by unit
     * @returns {number|null} m² by unit or null
     */
    getM2ByUnit() {
        return this.data.m2ByUnit || null;
    }
}

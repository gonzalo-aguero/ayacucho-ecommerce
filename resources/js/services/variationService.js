import { api } from './apiService';
import { handleError, logInfo } from '../utils/error';

export class VariationService {
    constructor() {
        this.options = [];
    }

    /**
     * Loads variations from the API
     * @returns {Promise<boolean>} Success status
     */
    async load() {
        try {
            const data = await api.getVariations();

            if (!Array.isArray(data)) {
                throw new Error('Invalid variations data format');
            }

            this.options = data;
            logInfo(`Loaded ${this.options.length} variations`, 'Variations.load');
            return true;
        } catch (error) {
            handleError(error, 'Variations.load');
            return false;
        }
    }

    /**
     * Gets a variation by ID
     * @param {number} variationId - Variation ID
     * @returns {Object|null} Variation object or null if not found
     */
    getById(variationId) {
        try {
            if (typeof variationId !== 'number' || variationId <= 0) {
                throw new Error('Variation ID must be a positive number');
            }

            const index = variationId - 1;
            if (index >= 0 && index < this.options.length) {
                return { ...this.options[index] }; // Return copy
            }
            return null;
        } catch (error) {
            handleError(error, 'Variations.get');
            return null;
        }
    }

    /**
     * Gets all variation values for a specific variation ID
     * @param {number} variationId - Variation ID
     * @returns {Array} Array of option values
     */
    getValues(variationId) {
        try {
            if (typeof variationId !== 'number' || variationId <= 0) {
                throw new Error('Variation ID must be a positive number');
            }

            const variation = this.getById(variationId);
            if (!variation || !Array.isArray(variation.options)) {
                return [];
            }

            return variation.options.map(option => option.value);
        } catch (error) {
            handleError(error, 'Variations.getValues');
            return [];
        }
    }

    /**
     * Gets a variation by index
     * @param {number} variationIndex - Variation index
     * @returns {Object|null} Variation object or null if invalid index
     */
    getByIndex(variationIndex) {
        try {
            if (typeof variationIndex !== 'number' || variationIndex < 0) {
                throw new Error('Variation index must be a non-negative number');
            }

            if (variationIndex >= 0 && variationIndex < this.options.length) {
                return { ...this.options[variationIndex] }; // Return copy
            }
            return null;
        } catch (error) {
            handleError(error, 'Variations.getByIndex');
            return null;
        }
    }

    /**
     * Finds and returns the option data according to the variation ID and an option value
     * @param {number} variationId - Variation ID
     * @param {string} optionValue - Option value
     * @returns {Object|null} Option data or null if not found
     */
    getByValue(variationId, optionValue) {
        try {
            if (typeof variationId !== 'number' || variationId <= 0) {
                throw new Error('Variation ID must be a positive number');
            }

            if (typeof optionValue !== 'string' || optionValue.trim() === '') {
                throw new Error('Option value must be a non-empty string');
            }

            const variation = this.getById(variationId);
            if (!variation || !Array.isArray(variation.options)) {
                return null;
            }

            const option = variation.options.find(opt => opt.value === optionValue);
            return option ? { ...option } : null; // Return copy
        } catch (error) {
            handleError(error, 'Variations.getByValue');
            return null;
        }
    }

    /**
     * Returns an array with all formatted texts with the option value
     * @param {number} variationId - Variation ID
     * @returns {Array} Array of formatted texts
     */
    getTexts(variationId) {
        try {
            if (typeof variationId !== 'number' || variationId <= 0) {
                throw new Error('Variation ID must be a positive number');
            }

            const variation = this.getById(variationId);
            if (!variation || !Array.isArray(variation.options)) {
                return [];
            }

            return variation.options.map((option, index) => ({
                index,
                name: option.value,
                value: option.value,
                units: option.units || 0
            }));
        } catch (error) {
            handleError(error, 'Variations.getTexts');
            return [];
        }
    }

    /**
     * Gets all variations
     * @returns {Array} All variations array
     */
    getAll() {
        return this.options.map(variation => ({ ...variation })); // Return copies
    }

    /**
     * Gets the number of variations
     * @returns {number} Number of variations
     */
    getCount() {
        return this.options.length;
    }

    /**
     * Checks if variations are loaded
     * @returns {boolean} True if variations are loaded
     */
    isLoaded() {
        return this.options.length > 0;
    }

    /**
     * Validates if a variation exists
     * @param {number} variationId - Variation ID
     * @returns {boolean} True if variation exists
     */
    exists(variationId) {
        try {
            if (typeof variationId !== 'number' || variationId <= 0) {
                return false;
            }

            const index = variationId - 1;
            return index >= 0 && index < this.options.length;
        } catch (error) {
            handleError(error, 'Variations.exists');
            return false;
        }
    }

    /**
     * Gets all option values across all variations
     * @returns {Array} Array of all option values
     */
    getAllOptionValues() {
        try {
            const allValues = [];
            this.options.forEach(variation => {
                if (Array.isArray(variation.options)) {
                    variation.options.forEach(option => {
                        if (option.value && !allValues.includes(option.value)) {
                            allValues.push(option.value);
                        }
                    });
                }
            });
            return allValues;
        } catch (error) {
            handleError(error, 'Variations.getAllOptionValues');
            return [];
        }
    }

    /**
     * Gets variations by option value
     * @param {string} optionValue - Option value to search for
     * @returns {Array} Array of variations containing the option value
     */
    getByOptionValue(optionValue) {
        try {
            if (typeof optionValue !== 'string' || optionValue.trim() === '') {
                throw new Error('Option value must be a non-empty string');
            }

            return this.options
                .filter(variation =>
                    Array.isArray(variation.options) &&
                    variation.options.some(option => option.value === optionValue)
                )
                .map(variation => ({ ...variation }));
        } catch (error) {
            handleError(error, 'Variations.getByOptionValue');
            return [];
        }
    }
}

export default VariationService;

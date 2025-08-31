"use strict";
import { api } from './apiService';
import { handleError, logInfo } from '../utils/errorHandler';

export class ShippingZoneService {
    constructor(zones = []) {
        this._validateZones(zones);
        this._zones = zones;
        this._selectedShippingZone = undefined;
    }

    /**
     * Validates shipping zones array
     * @param {Array} zones - Shipping zones array
     * @private
     */
    _validateZones(zones) {
        if (!Array.isArray(zones)) {
            throw new Error('Zones must be an array');
        }

        zones.forEach((zone, index) => {
            if (!zone || typeof zone !== 'object') {
                throw new Error(`Invalid zone at index ${index}`);
            }
            if (typeof zone.name !== 'string' || zone.name.trim() === '') {
                throw new Error(`Zone at index ${index} must have a valid name`);
            }
            if (typeof zone.cost !== 'number' || isNaN(zone.cost)) {
                throw new Error(`Zone at index ${index} must have a valid cost`);
            }
        });
    }

    /**
     * Gets the zones array
     * @returns {Array} Zones array
     */
    get zones() {
        return [...this._zones];
    }

    /**
     * Sets the zones array
     * @param {Array} zones - Zones array
     */
    set zones(zones) {
        this._validateZones(zones);
        this._zones = zones;
    }

    /**
     * Gets the selected shipping zone
     * @returns {Object|undefined} Selected shipping zone
     */
    get selectedShippingZone() {
        return this._selectedShippingZone;
    }

    /**
     * Sets the selected shipping zone
     * @param {Object} zone - Shipping zone object
     */
    set selectedShippingZone(zone) {
        if (zone === undefined || zone === null) {
            this._selectedShippingZone = undefined;
            return;
        }
        const found = this._zones.find(z => z.name === zone.name);
        if (found) {
            this._selectedShippingZone = { ...found };
        } else {
            throw new Error('Selected zone is not in the zones list');
        }
    }

    /**
     * Loads shipping zones from the API
     * @returns {Promise<boolean>} Success status
     */
    async load() {
        try {
            const data = await api.getShippingZones();

            if (!Array.isArray(data)) {
                throw new Error('Invalid shipping zones data format');
            }

            this._zones = data;
            logInfo(`Loaded ${this._zones.length} shipping zones`, 'ShippingZoneService.load');
            return true;
        } catch (error) {
            handleError(error, 'ShippingZoneService.load');
            return false;
        }
    }

    /**
     * Gets a shipping zone by index
     * @param {number} index - Zone index
     * @returns {Object|null} Shipping zone or null if invalid index
     */
    getByIndex(index) {
        if (index >= 0 && index < this._zones.length) {
            return { ...this._zones[index] };
        }
        return null;
    }

    /**
     * Gets a shipping zone by name
     * @param {string} name - Zone name
     * @returns {Object|null} Shipping zone or null if not found
     */
    getByName(name) {
        if (typeof name !== 'string') {
            return null;
        }

        const zone = this._zones.find(z => z.name.toLowerCase() === name.toLowerCase());
        return zone ? { ...zone } : null;
    }

    /**
     * Gets the number of shipping zones
     * @returns {number} Number of zones
     */
    getCount() {
        return this._zones.length;
    }

    /**
     * Checks if zones are loaded
     * @returns {boolean} True if zones are loaded
     */
    isLoaded() {
        return this._zones.length > 0;
    }

    /**
     * Returns a formatted text with the name and cost of the shipping zone
     * @param {Object} zone - Shipping zone object
     * @returns {string} Formatted text
     */
    getText(zone) {
        try {
            if (!zone || typeof zone !== 'object') {
                throw new Error('Invalid zone parameter');
            }

            const { name, cost } = zone;

            if (typeof name !== 'string' || typeof cost !== 'number') {
                throw new Error('Zone must have name and cost properties');
            }

            if (cost === 0) {
                return `${name} (Gratis)`;
            } else {
                return `${name}: $${cost}`;
            }
        } catch (error) {
            handleError(error, 'ShippingZoneService.getText');
            return 'Zona de envío inválida';
        }
    }

    /**
     * Returns all formatted texts with the name and cost of each shipping zone
     * @returns {Array} Array of formatted texts
     */
    getTexts() {
        try {
            return this._zones.map((zone, index) => ({
                index,
                name: this.getText(zone),
                originalName: zone.name,
                cost: zone.cost
            }));
        } catch (error) {
            handleError(error, 'ShippingZoneService.getTexts');
            return [];
        }
    }

    /**
     * Gets zones with zero cost (free shipping)
     * @returns {Array} Zones with free shipping
     */
    getFreeZones() {
        return this._zones
            .filter(zone => zone.cost === 0)
            .map(zone => ({ ...zone }));
    }

    /**
     * Gets zones with cost (paid shipping)
     * @returns {Array} Zones with paid shipping
     */
    getPaidZones() {
        return this._zones
            .filter(zone => zone.cost > 0)
            .map(zone => ({ ...zone }));
    }

    /**
     * Gets the total shipping cost for a specific zone
     * @param {Object} zone - Shipping zone object
     * @returns {number} Shipping cost
     */
    getShippingCost(zone) {
        try {
            if (!zone || typeof zone !== 'object') {
                throw new Error('Invalid zone parameter');
            }

            const { cost } = zone;
            if (typeof cost !== 'number' || isNaN(cost)) {
                throw new Error('Zone must have a valid cost property');
            }

            return cost;
        } catch (error) {
            handleError(error, 'ShippingZoneService.getShippingCost');
            return 0;
        }
    }

    /**
     * Gets zones sorted by cost (ascending)
     * @returns {Array} Zones sorted by cost
     */
    getZonesByCost() {
        return this._zones
            .slice()
            .sort((a, b) => a.cost - b.cost)
            .map(zone => ({ ...zone }));
    }

    /**
     * Gets zones sorted by name (alphabetical)
     * @returns {Array} Zones sorted by name
     */
    getZonesByName() {
        return this._zones
            .slice()
            .sort((a, b) => a.name.localeCompare(b.name))
            .map(zone => ({ ...zone }));
    }
}

export default ShippingZoneService;

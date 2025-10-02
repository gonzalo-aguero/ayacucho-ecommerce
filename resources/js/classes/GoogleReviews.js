import { api } from '../services/apiService';
import { handleError, logInfo } from '../utils/error';

export class GoogleReviews {
    constructor() {
        this.reviews = [];
        this.show = [];
        this.interval = null;
    }

    /**
     * Loads reviews from the API
     * @returns {Promise<boolean>} Success status
     */
    async load() {
        try {
            const data = await api.getGoogleReviews();

            if (!Array.isArray(data)) {
                throw new Error('Invalid reviews data format');
            }

            this.reviews = data;
            this.initializeShowArray();

            logInfo(`Loaded ${this.reviews.length} reviews`, 'GoogleReviews.load');
            return true;
        } catch (error) {
            handleError(error, 'GoogleReviews.load');
            return false;
        }
    }

    /**
     * Initializes the show array for UI state management
     */
    initializeShowArray() {
        this.show = new Array(this.reviews.length).fill(false);
    }

    /**
     * Gets all reviews
     * @returns {Array} Reviews array
     */
    getReviews() {
        return [...this.reviews]; // Return copy to prevent external modification
    }

    /**
     * Gets a specific review by index
     * @param {number} index - Review index
     * @returns {Object|null} Review object or null if invalid index
     */
    getReview(index) {
        if (index >= 0 && index < this.reviews.length) {
            return { ...this.reviews[index] }; // Return copy
        }
        return null;
    }

    /**
     * Gets the number of reviews
     * @returns {number} Number of reviews
     */
    getCount() {
        return this.reviews.length;
    }

    /**
     * Checks if reviews are loaded
     * @returns {boolean} True if reviews are loaded
     */
    isLoaded() {
        return this.reviews.length > 0;
    }

    /**
     * Gets the current show state array
     * @returns {Array} Show state array
     */
    getShowStates() {
        return [...this.show]; // Return copy
    }

    /**
     * Sets the show state for a specific review
     * @param {number} index - Review index
     * @param {boolean} state - Show state
     */
    setShowState(index, state) {
        if (index >= 0 && index < this.show.length) {
            this.show[index] = Boolean(state);
        }
    }

    /**
     * Shows a specific review
     * @param {number} index - Review index
     */
    showReview(index) {
        if (index >= 0 && index < this.show.length) {
            // Hide all reviews first
            this.show.fill(false);
            // Show the specified review
            this.show[index] = true;
        }
    }

    /**
     * Hides all reviews
     */
    hideAllReviews() {
        this.show.fill(false);
    }

    /**
     * Starts the automatic review rotation
     * @param {number} intervalMs - Interval in milliseconds (default: 6000)
     */
    startRotation(intervalMs = 6000) {
        if (this.interval) {
            this.stopRotation();
        }

        if (this.reviews.length === 0) {
            logInfo('No reviews to rotate', 'GoogleReviews.startRotation');
            return;
        }

        // Show first review
        this.showReview(0);

        let currentIndex = 0;
        this.interval = setInterval(() => {
            currentIndex = (currentIndex + 1) % this.reviews.length;
            this.showReview(currentIndex);
        }, intervalMs);

        logInfo(`Started review rotation with ${intervalMs}ms interval`, 'GoogleReviews.startRotation');
    }

    /**
     * Stops the automatic review rotation
     */
    stopRotation() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
            logInfo('Stopped review rotation', 'GoogleReviews.stopRotation');
        }
    }

    /**
     * Gets the current active review index
     * @returns {number} Current active index or -1 if none
     */
    getCurrentActiveIndex() {
        return this.show.findIndex(state => state === true);
    }

    /**
     * Destroys the instance and cleans up resources
     */
    destroy() {
        this.stopRotation();
        this.reviews = [];
        this.show = [];
    }
}

export default GoogleReviews;

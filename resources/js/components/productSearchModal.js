/**
 * Product Search Modal Component
 * Handles the search functionality and UI interactions for the product search modal
 */

export function initProductSearchModal() {
    // Register the Alpine component globally
    document.addEventListener('alpine:init', () => {
        Alpine.data('productSearchModal', () => ({
            searchQuery: '',
            searchResults: [],
            isLoading: false,
            searchTimeout: null,
            debounceTime: 200, // milliseconds

            init() {
                // Focus on input when modal opens
                this.$watch('$store.searchModalOpened', (value) => {
                    if (value) {
                        this.$nextTick(() => {
                            this.$refs.searchInput?.focus();
                        });
                    } else {
                        // Clear search when closing
                        this.searchQuery = '';
                        this.searchResults = [];
                    }
                });
            },

            handleSearch() {
                // Clear previous timeout
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }

                // If query is empty, clear results
                if (this.searchQuery.trim().length === 0) {
                    this.searchResults = [];
                    this.isLoading = false;
                    return;
                }

                // Show loading state
                this.isLoading = true;

                // Debounce search
                this.searchTimeout = setTimeout(() => {
                    this.performSearch();
                }, this.debounceTime);
            },

            performSearch() {
                try {
                    const results = Alpine.store('productService').searchProducts(this.searchQuery.trim());
                    this.searchResults = results;
                } catch (error) {
                    console.error('Error searching products:', error);
                    this.searchResults = [];
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
}

{{-- Product Search Modal Component --}}
<div x-show="$store.searchModalOpened"
     x-cloak
     class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4"
     x-data="productSearchModal"
     @keydown.escape.window="$store.searchModalOpened = false"
     x-transition:enter="animate__animated animate__fadeIn very_fast_animation"
     x-transition:leave="animate__animated animate__fadeOut very_fast_animation">

    {{-- Overlay --}}
    <div class="fixed inset-0 bg-black/50" @click="$store.searchModalOpened = false"></div>

    {{-- Modal Content --}}
    <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[70vh] overflow-hidden"
         x-transition:enter="animate__animated animate__zoomIn very_fast_animation"
         x-transition:leave="animate__animated animate__zoomOut very_fast_animation">

        {{-- Header --}}
        <div class="p-4 border-b border-gray-light2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-dark">Buscar Productos</h2>
                <button @click="$store.searchModalOpened = false"
                        class="text-gray hover:text-gray-dark rounded-full p-1 hover:bg-gray-light">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Search Input --}}
        <div class="p-4 border-b border-gray-light">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text"
                       x-model="searchQuery"
                       x-ref="searchInput"
                       @input="handleSearch"
                       placeholder="Buscar por nombre o código del producto..."
                       class="w-full pl-10 pr-4 py-3 border border-gray-light2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-medium focus:border-transparent">
            </div>
        </div>

        {{-- Results Section --}}
        <div class="flex-1 overflow-y-auto max-h-96">
            {{-- Loading State --}}
            <div x-show="isLoading" class="p-8 text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-orange-medium"></div>
                <p class="mt-2 text-gray">Buscando productos...</p>
            </div>

            {{-- No Results --}}
            <div x-show="!isLoading && searchQuery.length > 0 && searchResults.length === 0"
                 class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-light2 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray text-lg">No se encontraron productos</p>
                <p class="text-gray-light2 text-sm mt-1">Intenta con otros términos de búsqueda</p>
            </div>

            {{-- Empty State --}}
            <div x-show="!isLoading && searchQuery.length === 0" class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-light2 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray">Escribe para buscar productos</p>
                <p class="text-gray-light2 text-sm mt-1">Busca por nombre o código</p>
            </div>

            {{-- Results Grid --}}
            <div x-show="!isLoading && searchResults.length > 0" class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="product in searchResults" :key="product.id">
                        <div class="bg-white border border-gray-light2 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200"
                             x-data="{
                                 units: 1,
                                 productImage(){
                                     const image = product.image !== null
                                         ? '{{ asset('images/products') }}/' + product.id + '.' + product.image
                                         : '{{ asset('images/defaultImage.svg') }}';
                                     return image;
                                 },
                                 addToCart(){
                                     if($store.productService.addToCart(this.units, product)){
                                         this.units = 1;
                                     }
                                 }
                             }">

                            {{-- Product Image --}}
                            <div class="relative">
                                <template x-if="product.units == 0">
                                    <div class="bg-red text-white font-medium text-center rounded-t absolute w-full opacity-80 z-10">SIN STOCK</div>
                                </template>
                                <a :href="$store.productService.getProductPageUrl(product)" @click="$store.searchModalOpened = false">
                                    <img class="h-32 w-full object-cover" :src="productImage" :alt="product.name" :title="product.description">
                                </a>
                            </div>

                            {{-- Product Info --}}
                            <div class="p-3">
                                <h3 class="font-medium text-sm mb-2 line-clamp-2">
                                    <a :href="$store.productService.getProductPageUrl(product)"
                                       @click="$store.searchModalOpened = false"
                                       x-text="product.name"
                                       class="hover:text-orange-medium"></a>
                                </h3>

                                {{-- Price --}}
                                <div class="text-center font-light mb-3">
                                    <span class="text-base font-medium" x-text="$store.priceFormat(product.price)"></span>
                                    <span class="text-xs block" x-text="$store.priceFormat(product.m2Price) + '/m²'" x-show="$store.productService.measurableInM2(product)"></span>
                                </div>

                                {{-- Actions --}}
                                <template x-if="product.variationId === null">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex items-center gap-2 w-full justify-center">
                                            <input type="number" min="1" x-model.number="units" :disabled="product.units == 0"
                                                class="w-16 text-sm rounded border border-gray-light2 text-center py-1"/>
                                            <span class="text-xs text-gray">
                                                <template x-if="window.boxedCategories && window.boxedCategories.includes(product.category)">
                                                    <span>Cajas</span>
                                                </template>
                                                <template x-if="$store.productService.measurableInM2(product)">
                                                    <span>m²</span>
                                                </template>
                                                <template x-if="!window.boxedCategories?.includes(product.category) && !$store.productService.measurableInM2(product)">
                                                    <span>Unid.</span>
                                                </template>
                                            </span>
                                        </div>
                                        <div class="text-xs font-normal text-center" x-show="$store.productService.measurableInM2(product)">
                                            <span class="text-sm">=</span>
                                            <span x-text="$store.productService.squareMeters(units, product)"></span>
                                            <span>m²</span>
                                        </div>
                                        <button @click="addToCart"
                                                :disabled="product.units == 0"
                                                class="w-full text-white text-sm py-2 px-3 rounded transition-colors"
                                                :class="product.units == 0
                                                    ? 'bg-gray opacity-80 cursor-not-allowed'
                                                    : 'bg-orange-light hover:bg-orange-medium active:scale-95'">
                                            Añadir al carrito
                                        </button>
                                    </div>
                                </template>

                                <template x-if="product.variationId !== null">
                                    <div class="text-center">
                                        <a :href="$store.productService.getProductPageUrl(product)"
                                           class="inline-block w-full bg-orange-light hover:bg-orange-medium text-white text-sm py-2 px-3 rounded transition-colors">
                                            Ver opciones
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-4 border-t border-gray-light bg-gray-light">
            <div class="flex justify-between items-center text-sm text-gray">
                <span>Presiona <kbd class="px-2 py-1 text-xs bg-white border border-gray-light2 rounded">Ctrl+K</kbd> para abrir</span>
                <span>Presiona <kbd class="px-2 py-1 text-xs bg-white border border-gray-light2 rounded">Esc</kbd> para cerrar</span>
            </div>
        </div>
    </div>
</div>

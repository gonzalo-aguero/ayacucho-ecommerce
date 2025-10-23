@props([
    'closeModalOnAction' => false,
    'compactMode' => false
])

<div class="relative text-black rounded shadow-lg shrink-0 {{ $compactMode ? 'bg-white border border-gray-light2 overflow-hidden hover:shadow-lg transition-shadow duration-200' : 'bg-gray-light w-40 border-t-0 border border-gray-light-transparent' }}"
        x-data="{
            units: 1,
            productImage(){
                const image =
                    product.image !== null
                    ? '{{ asset('images/products') }}/' + product.id + '.' + product.image
                    : '{{ asset('images/defaultImage.svg') }}';
                return image;
            },
            addToCart(){
                if($store.productService.addToCart(this.units, product)){
                    this.units = 1;
                    @if($closeModalOnAction)
                        $store.searchModalOpened = false;
                    @endif
                }
            }
        }"
        >
        {{--"NO STOCK" SIGN--}}
        <template x-if="product.units == 0">
            <div class="bg-red text-white font-medium text-center rounded-t absolute w-full opacity-80 {{ $compactMode ? 'z-10' : '' }}">SIN STOCK</div>
        </template>

        {{--PRODUCT IMAGE--}}
        <div class="shrink-0 {{ $compactMode ? '' : 'mb-2' }}">
            <a :href="$store.productService.getProductPageUrl(product)" @if($closeModalOnAction) @click="$store.searchModalOpened = false" @endif>
                <img class="w-full object-cover rounded-t {{ $compactMode ? 'h-32' : 'h-40' }}"
                     :src="productImage" :alt="product.name" :title="product.description">
            </a>
        </div>

        {{--CONTENT WRAPPER (for compact mode padding)--}}
        <div class="{{ $compactMode ? 'p-3' : '' }}">
            {{--PRODUCT NAME--}}
            <h3 class="text-center text-sm font-medium mb-1 {{ $compactMode ? 'mb-2 line-clamp-2' : '' }}">
                <a :href="$store.productService.getProductPageUrl(product)"
                   x-text="product.name"
                   class="{{ $compactMode ? 'hover:text-orange-medium' : '' }}"
                   @if($closeModalOnAction) @click="$store.searchModalOpened = false" @endif></a>
            </h3>

            {{--PRICE SECTION--}}
            <div class="text-center font-light {{ $compactMode ? 'mb-3' : '' }}">
                <!-- Primary price -->
                <span class="text-base {{ $compactMode ? 'font-medium' : '' }}" x-text="$store.priceFormat(product.price)"></span>
                <!-- Secondary price -->
                <span class="text-xs {{ $compactMode ? 'block' : '' }}" x-text="$store.priceFormat(product.m2Price) + '/m²'" x-show="$store.productService.measurableInM2(product)"></span>
            </div>

            <template x-if="product.variationId === null">
                {{--ADD TO CART SECTION--}}
                <div class="flex flex-col w-full items-center pt-2 pb-3 {{ $compactMode ? 'gap-2' : '' }}"
                     x-data="{
                        unitsText(){
                            if($store.productService.measurableInM2(product)){
                                return product.units + 'm² disponibles';
                            }
                            return product.units + ' disponibles';
                        }
                    }">
                    <div class="flex w-full justify-center items-center {{ $compactMode ? 'gap-2' : 'gap-1' }}">
                        <input type="number" min="1" x-model.number="units" :disabled="product.units == 0"
                            class="text-sm rounded border border-gray-light2 text-center {{ $compactMode ? 'w-16 py-1' : 'block w-12' }}"/>

                        <template x-if="boxedCategories.includes(product.category)">
                            <span class="text-xs font-light {{ $compactMode ? 'text-gray' : '' }}">Cajas</span>
                        </template>

                        <template x-if="!boxedCategories.includes(product.category)">
                            <span class="text-xs font-light {{ $compactMode ? 'text-gray' : '' }}">Unidades</span>
                        </template>
                    </div>
                    <div class="text-xs font-normal {{ $compactMode ? 'text-center' : '' }}"
                         x-show="$store.productService.measurableInM2(product)">
                        <span class="text-sm">=</span>
                        <span x-text="$store.productService.squareMeters(units, product)"></span>
                        <span>m²</span>
                    </div>
                    <button class="text-white text-sm mt-2 rounded {{ $compactMode ? 'w-full py-2 px-3 transition-colors' : 'p-1' }}" name="Añadir al carrito"
                        :class="product.units == 0
                            ? 'bg-gray opacity-80{{ $compactMode ? ' cursor-not-allowed' : '' }}'
                            : 'bg-orange-light active:scale-95{{ $compactMode ? ' hover:bg-orange-medium' : ' active:opacity-80 hover:opacity-80' }}'"
                        x-on:click="addToCart"
                        :disabled="product.units == 0"
                        >Añadir al carrito</button>
                    <template x-if="product.showUnits">
                        <span class="text-xs font-light mt-2 text-gray {{ $compactMode ? 'text-center' : '' }}" x-text="unitsText"></span>
                    </template>
                </div>
            </template>

            <template x-if="product.variationId !== null">
                {{--SEE OPTIONS SECTION--}}
                <div class="flex flex-col w-full items-center pt-2 pb-3 {{ $compactMode ? 'text-center' : '' }}">
                    <a class="bg-orange-light active:scale-95 text-white text-sm mt-2 rounded {{ $compactMode ? 'inline-block w-full py-2 px-3 transition-colors hover:bg-orange-medium' : 'active:opacity-80 hover:opacity-80 p-1' }}" name="Ver opciones"
                        :href="$store.productService.getProductPageUrl(product)"
                        @if($closeModalOnAction) @click="$store.searchModalOpened = false" @endif
                        >Ver opciones</a>
                </div>
            </template>
        </div>
    </div>

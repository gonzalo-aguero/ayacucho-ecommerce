<div x-data="{
        loadingProducts: ()=>{
            return $store.productService.productsToPrint.length == 0;
        }
    }" class="flex flex-col items-center justify-center w-[95%] sm:w-5/6 bg-white rounded-md shadow-2xl">
    <template x-for="category in $store.productService.productsToPrint">
        <div x-data="{ open: false }" class="block w-full shadow">
            <h2 x-text="category.category" class="text-center py-3 text-xl uppercase cursor-pointer text-orange-medium font-bold
                hover:bg-gray-light rounded-md hover:underline decoration-orange-medium underline-offset-2" @click="open = !open"></h2>
            <div class="flex gap-2 justify-left w-full overflow-auto p-2 bg-gray-light"
                x-show="open"
                x-transition:enter="dropdown_menu-6"
                x-transition:leave="dropup_menu-6"
                >
                <template x-for="product in category.products.filter(product => product.show)">
                    {{--
                        --------------------------
                        Product card template
                        ---------------------------
                    ---}}
                    <div class="relative bg-gray-light text-black w-40 rounded shadow-lg border-t-0 border border-gray-light-transparent shrink-0"
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
                                }
                            }
                        }"
                        >
                        {{--"NO STOCK" SIGN--}}
                        <template x-if="product.units == 0">
                            <div class="bg-red text-white font-medium text-center rounded-t absolute w-full opacity-80">SIN STOCK</div>
                        </template>
                        {{--PRODUCT NAME--}}
                        <div class="shrink-0 mb-2">
                            <a :href="$store.productService.getProductPageUrl(product)">
                                <img class="h-40 w-full object-cover rounded-t" :src="productImage" :alt="product.name" :title="product.description">
                            </a>
                        </div>
                        <h3 class="text-center text-sm font-medium mb-1"><a :href="$store.productService.getProductPageUrl(product)" x-text="product.name"></a></h3>

                        {{--PRICE SECTION--}}
                        <div class="text-center font-light">
                            <!-- Primary price -->
                            <span class="text-base" x-text="$store.priceFormat(product.price)"></span>
                            <!-- Secondary price -->
                            <span class="text-xs" x-text="$store.priceFormat(product.m2Price) + '/m²'" x-show="$store.productService.measurableInM2(product)"></span>
                        </div>


                        <template x-if="product.variationId === null">
                            {{--ADD TO CART SECTION--}}
                            <div class="flex flex-col w-full items-center pt-2 pb-3" x-data="{
                                    unitsText(){
                                        if($store.productService.measurableInM2(product)){
                                            return product.units + 'm² disponibles';
                                        }
                                        return product.units + ' disponibles';
                                    }
                                }">
                                <div class="flex w-full justify-center items-center gap-1">
                                    <input type="number" min="1" x-model.number="units" :disabled="product.units == 0"
                                        class="block w-12 text-sm rounded border border-gray-light2 text-center"/>

                                    <template x-if="boxedCategories.includes(product.category)">
                                        <span class="text-xs font-light">Cajas</span>
                                    </template>

                                    <template x-if="!boxedCategories.includes(product.category)">
                                        <span class="text-xs font-light">Unidades</span>
                                    </template>
                                </div>
                                <div class="text-xs font-normal" x-show="$store.productService.measurableInM2(product)">
                                    <span class="text-sm">=</span>
                                    <span x-text="$store.productService.squareMeters(units, product)"></span>
                                    <span>m²</span>
                                </div>
                                <button class="text-white text-sm p-1 mt-2 rounded " name="Añadir al carrito"
                                    :class=" product.units == 0
                                        ? 'bg-gray opacity-80'
                                        : 'bg-orange-light active:opacity-80 hover:opacity-80 active:scale-95'"
                                    x-on:click="addToCart"
                                    :disabled="product.units == 0"
                                    >Añadir al carrito</button>
                                <template x-if="product.showUnits">
                                    <span class="text-xs font-light mt-2 text-gray" x-text="unitsText"></span>
                                </template>
                            </div>
                        </template>
                        <template x-if="product.variationId !== null">
                            {{--SEE OPTIONS SECTION--}}
                            <div class="flex flex-col w-full items-center pt-2 pb-3">
                                <a class="bg-orange-light active:opacity-80 hover:opacity-80 active:scale-95 text-white text-sm p-1 mt-2 rounded" name="Ver opciones"
                                    :href="$store.productService.getProductPageUrl(product)"
                                    >Ver opciones</a>
                            </div>
                        </template>
                    </div>
                    {{---------------------------
                        END Product card template
                    -------------------------------}}
                </template>
            </div>
        </div>
     </template>
    @for ($i = 0; $i < 6; $i++)
        <template x-if="loadingProducts">
            <div x-data="{ open: false }" class="block w-full bg-white rounded shadow-lg">
                <h2 class="h-[52px] flex justify-center items-center uppercase cursor-pointer font-bold shadow drop-shadow-lg animate-pulse" @click="open = !open">
                    <div class="bg-orange w-40 py-2 rounded opacity-70"></div>
                </h2>
                <div class="flex gap-2 justify-left w-full overflow-auto p-2 dropdown_menu-6 bg-gray-light" x-show="open">
                    @for($j = 0; $j < 10; $j++)
                       <x-product.loading-product-card></x-product.loading-product-card>
                    @endfor
                </div>
            </div>
        </template>
    @endfor
</div>

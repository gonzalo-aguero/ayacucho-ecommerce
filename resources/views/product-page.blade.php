@props([
    'pageTitle' => $product->name. " - " . config('app.name'),
    "pageDescription" => $product->description,


    "javascriptData" => [
        ["key" => "product", "value" => $product],
        ["key" => "variation", "value" => $variation],
    ],
    {{--Measurable per square meter--}}
    "squareMeter" => $product->m2Price != null && $product->m2ByUnit != null,
    "hasAttributes" => ($product->m2Price != null && $product->m2ByUnit != null),
    "disabledAddToCartCondition" => 'product.units === 0 || (product.variationId !== null && undefined === $store.selectedVariation)',
])
<x-store-layout
    pageTitle="{{$pageTitle}}"
    :$pageDescription
    :revisit="true"
    isProductPage="true"
    :$javascriptData
    >
    <div class="relative flex flex-wrap justify-center gap-8 mt-40 mb-20 px-4 py-10 bg-white" x-data="{
            units: 1,
            addToCart(){
                if($store.StaticProduct.addToCart(this.units, product, $store.selectedVariation))
                    this.units = 1;
            }
        }">
        {{--MAIN INFO SECTION--}}
        <div>
            {{--PRODUCT IMAGE--}}
            <div class="relative text-center">
                {{--"NO STOCK" SIGN--}}
                <div x-cloak class="bg-red text-white text-center rounded-t absolute w-full opacity-80"
                    x-show="
                    (product.variationId === null && product.units === 0)
                    || (product.variationId !== null
                        && undefined !== $store.selectedVariation
                        && $store.variations.getByValue(product.variationId, $store.selectedVariation).units === 0
                        )">SIN STOCK</div>

                <img
                    src="{{
                        $product->image != null && $product->image != ""
                        ? asset('images/products/'. $product->id . "." . $product->image)
                        : asset('images/defaultImage.svg');
                    }}"
                    class="w-80 h-auto object-cover"
                    alt="{{ $product->name }}" title="{{ $product->description }}"
                >
            </div>

            {{--PRODUCT NAME--}}
            <h1 class="text-2xl font-semibold my-2 text-center">{{ $product->name }}</h1>

            {{--PRICE SECTION--}}
            <div class="text-center font-light">
                <!-- Primary price -->
                <span class="text-2xl" x-text="$store.priceFormat(product.price)">{{ $product->price }}</span>
                <!-- Secondary price -->
                @if($squareMeter)
                    <span class="text-base" x-text="$store.priceFormat(product.m2Price) + '/m²'">{{ $product->m2Price }}</span>
                @endif
            </div>

            {{--ADD TO CART SECTION--}}
            <div class="flex flex-col m-w-80 pt-2 pb-3">
                <div class="flex w-full justify-center items-center gap-1">
                    <input type="number" min="1" {{ $product->showUnits ? 'max="'. $product->units .'"' : "" }} x-model="units" :disabled="product.units == 0"
                        class="block w-16 text-xl font-light rounded border border-gray-light2 text-center"/>
                    <span class="text-base font-light">
                        @if($product->category == "REVESTIMIENTOS PARED"
                                OR $product->category == "CERÁMICOS"
                                OR $product->category == "PORCELANATOS"
                                OR $product->category == "CERAMICOS PREMIUM"
                            )
                            Cajas
                        @else
                            Unidades
                        @endif
                    </span>

                    {{--AVAILABLE UNITS SECTION--}}
                    @if($product->showUnits)
                        @if(!$squareMeter)
                            @if($product->variationId == null)
                                <span class="text-base font-light">({{$product->units}} disponibles)</span>
                            @else
                                <template x-if="$store.selectedVariation">
                                    <span class="text-base font-light" x-text="
                                        '('
                                        + $store.variations.getByValue(product.variationId, $store.selectedVariation).units
                                        + ' disponibles)'
                                    "></span>
                                </template>
                            @endif
                        @endif
                    @endif
                </div>

                {{--VARIATION SELECTOR SECTION--}}
                @if($product->variationId != null)
                    <div class="mt-2" x-data="variationSelect">
                        <x-form.input type="select" name="variation"
                            required
                            requiredSign="1"
                            getSelectedFrom="$store.variations.getValues({{ $product->variationId }})"
                            saveSelectedIn="$store.selectedVariation"
                            >
                            <span class="text-base font-medium" x-text="undefined !== variation ? variation.title : ''"></span>
                        </x-form.input>
                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.data('variationSelect', ()=>({
                                    showDefaultOption: true,//set true to show the option "Select"
                                    defaultOptionText: "Seleccionar",
                                    showBladeOptions: false,
                                    options: [],
                                    variation: undefined,
                                    init() {
                                        this.$watch('$store.variations', (val) => {
                                            const texts = Alpine.store("variations").texts({{ $product->variationId }});
                                            this.options = texts;
                                            this.variation = Alpine.store("variations").get({{ $product->variationId }});
                                        });
                                    }
                                }));
                            });
                        </script>
                    </div>
                @endif


                @if($squareMeter)
                    <div class="text-base font-normal text-center">
                        <span class="text-lg">=</span>
                        <span x-text="$store.StaticProduct.squareMeters(units, product)"></span>
                        <span>m²</span>

                        {{--AVAILABLE UNITS SECTION 2--}}
                        @if($product->showUnits)
                            @if($product->variationId == null)
                                <span class="text-base font-light">({{number_format($product->units, 2, ',', '.')}}m² disponibles)</span>
                            @else
                                <template x-if="$store.selectedVariation">
                                    <span class="text-base font-light" x-text="
                                        '('
                                        + $store.decimalFormat(
                                                $store.variations.getByValue(product.variationId, $store.selectedVariation).units
                                            )
                                        + 'm² disponibles)'
                                    "></span>
                                </template>
                            @endif
                        @endif
                    </div>
                @endif


                {{--ADD TO CART BUTTON--}}
                <button class="text-white text-xl p-2 mt-2 rounded"
                    x-bind:class="{{ $disabledAddToCartCondition }}
                        ? 'bg-gray opacity-80'
                        : 'bg-orange-light active:opacity-80 hover:opacity-80 active:scale-95'"
                        x-on:click="addToCart"
                    :disabled="{{ $disabledAddToCartCondition }}"
                    >Añadir al carrito</button>
            </div>
        </div>

        {{--More info section--}}
        <div class="border border-gray-light2 bg-gray-light-transparent rounded-md px-4 py-6">
            <div class="mb-4">
                <h2 class="text-lg font-medium uppercase">Descripción</h2>
                <p>{{ $product->description }}</p>
            </div>
            <div class="mb-4">
                <h2 class="text-lg font-medium uppercase">Categoría</h2>
                <p>{{ $product->category }}</p>
            </div>
            @if($hasAttributes)
                <div class="mb-4">
                    <h2 class="text-lg font-medium uppercase mt-2">Características</h2>
                    @if($squareMeter)
                        <div class="text-base">
                            <h3 class="inline-block">Metros cuadrados por unidad: </h3>
                            <span>{{ number_format($product->m2ByUnit, 2, ',', '.') }}</span>
                        </div>
                        <div class="text-base">
                            <h3 class="inline-block">Precio por metro cuadrado: </h3>
                            <span>${{ number_format($product->m2Price, 2, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-store-layout>

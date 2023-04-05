<div class="w-full h-full flex flex-col">
    <h2 class="text-center font-bold text-lg uppercase text-orange mb-2">Tu carrito</h2>
    <div class="grid grid-cols-cart-table grid-flow-row text-xs font-semibold bg-gray-light-transparent">
        <span></span>
        <span>Producto</span>
        <span>Precio</span>
        <span>Unidades</span>
        <span>Subtotal</span>
    </div>
    <ul class="h-full md:w-full overflow-auto flex flex-col gap-4">
        <template x-if="$store.productsLength > 0 && $store.cart.content.length > 0">
            <template x-for="item in $store.cart.content">
                <li x-data="{
                        units: 1,
                        product: 0,
                        variation: '',
                        defaultImage(){
                            return this.product.thumbnail !== null ? this.product.thumbnail :'{{ asset('images/defaultImage.svg') }}';
                        }

                    }"
                    x-init="
                        product = $store.products[item.pos];
                        units = item.units;
                        $watch('$store.cart.content', ()=>{
                            product = $store.products[item.pos];
                            units = item.units;
                        });
                    "
                    class="grid grid-cols-cart-table gap-2 text-sm items-center p-2">
                    {{--REMOVE PRODUCT BUTTON--}}
                    <button class="w-5 hover:opacity-80" @click="
                        if($store.Confirm(`Se eliminará ${product.name} de tu carrito.\n¿Estás seguro?`)){
                            $store.cart.remove(item);
                        }
                    ">
                        <img src="{{ asset('images/UI-Icons/icons8-remove-48.png') }}" class="h-5 w-5"/>
                    </button>
                    {{--PRODUCT IMAGE AND NAME--}}
                    <a :href="$store.StaticProduct.productPage(product)" class="flex flex-nowrap gap-2">
                        <img :src="defaultImage" class="h-6 w-6"/>
                        <span x-text="product.name + variation" class="truncate text-xs break-words" :title="product.name"></span>
                    </a>
                    {{--PRODUCT PRICES--}}
                    <div>
                        <span x-text="$store.priceFormat(product.price)"></span>
                        <template x-if="$store.StaticProduct.measurableInM2(product)">
                            <div class="flex flex-nowrap">
                                <span x-text="$store.priceFormat(product.m2Price)" class="text-xs"></span>
                                <span class="text-xs">/m²</span>
                            </div>
                        </template>
                    </div>
                    {{--PRODUCT UNITS--}}
                    <div>
                        <input type="number" min="1" x-model.lazy="units" x-init="
                            $watch('units', (value, valueBef) => {
                                if(value === '') units = valueBef;
                                else{
                                    item.units = Number.parseInt(value);
                                    $store.cart.save();
                                }
                            });
                            $watch('item.units', value => units = value);
                        " class="w-12 bg-gray-light-transparent border border-gray-light2"/>
                        <template x-if="$store.StaticProduct.measurableInM2(product)">
                            <div class="flex flex-nowrap">
                                <span x-text="'=' + $store.StaticProduct.squareMeters(units, product)" class="text-xs"></span>
                                <span class="text-xs">m²</span>
                            </div>
                        </template>
                    </div>
                    {{--PRODUCT SUBTOTAL--}}
                    <span x-text="$store.priceFormat(product.price * Number.parseInt(units))"></span>
                </li>
            </template>
        </template>
    </ul>
    <div class="flex justify-between flex-wrap md:flex-nowrap  md:items-stretch h-20 md:h-auto">
        <button x-show="$store.cart.length() > 0" class="bg-red text-white py-1 px-2 text-sm rounded hover:opacity-80 w-[48%] md:w-auto" @click="
            if($store.Confirm('Se vaciará tu carrito.\n¿Estás seguro?')){
                $store.cart.clear();
                $store.cartOpened = false;
            }
        ">Vaciar Carrito</button>
        <button x-show="$store.cart.length() === 0" disabled class="bg-gray text-white py-1 px-2 text-sm rounded opacity-80 w-[48%] md:w-auto">Vaciar Carrito</button>

        <template x-if="$store.productsLength > 0">
            <div class="text-center order-first md:order-none w-full md:w-auto">
                <span class="font-semibold">TOTAL: </span>
                <span x-text="$store.priceFormat($store.cart.total())"></span>
            </div>
        </template>

        {{--BOTON Finalizar pedido--}}
        <button x-show="$store.cart.length() === 0" @click="$store.gotoCheckout('{{ route("checkout") }}')" class="bg-gray cursor-default text-white py-1 px-2 text-sm rounded opacity-80 w-[48%] md:w-auto">Ir a Finalizar Pedido</button>
        <button x-show="$store.cart.length() > 0" @click="$store.gotoCheckout('{{ route("checkout") }}')" class="bg-green text-white py-1 px-2 text-sm rounded hover:opacity-80 w-[48%] md:w-auto">Ir a Finalizar Pedido</button>
    </div>
</div>

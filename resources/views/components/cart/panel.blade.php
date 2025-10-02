<div class="w-full h-full flex flex-col">
    <h2 class="text-center font-bold text-lg uppercase text-orange mb-2">Tu carrito</h2>
    <div class="grid grid-cols-cart-table grid-flow-row text-xs font-semibold bg-gray-light-transparent">
        <span></span>
        <span>Producto</span>
        <span>Precio</span>
        <span>Unidades / Cajas</span>
        <span>Subtotal</span>
    </div>
    <ul class="h-full md:w-full overflow-auto flex flex-col gap-4">
        <template x-if="$store.productService.products.length > 0 && $store.cart.content.length > 0">
            <template x-for="item in $store.cart.content">
                <li x-data="{
                        units: 1,
                        product: 0,
                        optionValue: '',
                        productImage(){
                            const image =
                                this.product.image !== null
                                ? '{{ asset('images/products') }}/' + this.product.id + '.' + this.product.image
                                : '{{ asset('images/defaultImage.svg') }}';
                            return image;
                        }
                    }"
                    x-init="
                        product = $store.productService.products[item.pos];
                        units = item.units;
                        if(undefined !== item.option) optionValue = item.option;
                        $watch('$store.cart.content', ()=>{
                            product = $store.productService.products[item.pos];
                            units = item.units;
                            if(undefined !== item.option) optionValue = item.option;
                        });
                    "
                    class="grid grid-cols-cart-table gap-2 text-sm items-center p-2">
                    {{--REMOVE PRODUCT BUTTON--}}
                    <button class="w-5 hover:opacity-80" @click="
                        $store.Confirm(`Se eliminará ${product.name} de tu carrito.\n¿Estás seguro?`, (response)=>{
                            if(response){
                                $store.cart.remove(item, optionValue);
                            }
                        });
                    ">
                        <img src="{{ asset('images/UI-Icons/icons8-remove-48.png') }}" class="h-5 w-5"/>
                    </button>
                    {{--PRODUCT IMAGE AND NAME--}}
                    <a :href="$store.productService.getProductPageUrl(product)" class="flex flex-nowrap gap-2" x-data="{
                            productNameText(){
                                let productName = product.name;
                                if(product.name.length > 25) productName = product.name.substring(0, 22) + '...';

                                if(undefined === optionValue || optionValue === '') return productName;
                                else return productName + ' - ' + optionValue;
                            },
                            fullProductName(){
                                let productName = product.name;

                                if(undefined === optionValue || optionValue === '') return productName;
                                else return productName + ' - ' + optionValue;
                            }
                        }">
                        <img :src="productImage" class="h-6 w-6"/>
                        <span x-text="productNameText" class="truncate text-xs break-words" :title="fullProductName"></span>
                    </a>
                    {{--PRODUCT PRICES--}}
                    <div>
                        <span x-text="$store.priceFormat(product.price)"></span>
                        <template x-if="$store.productService.measurableInM2(product)">
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
                                if(value === '' || value == '0' || value < 0) units = valueBef;
                                else{
                                    const newUnits = Number.parseInt(value);
                                    if($store.productService.validUnits(newUnits, product, optionValue)){
                                        item.units = newUnits;
                                        $store.cart.save();
                                    }else{
                                        units = $store.productService.maxAvailableUnits(product, optionValue);
                                        Alpine.store('Notify').Warning('No hay suficiente stock disponible.');
                                    }
                                }
                            });
                            $watch('item.units', value => units = value);
                        " class="w-12 bg-gray-light-transparent border border-gray-light2"/>
                        <template x-if="$store.productService.measurableInM2(product)">
                            <div class="flex flex-nowrap">
                                <span x-text="'=' + $store.productService.squareMeters(units, product)" class="text-xs"></span>
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
            $store.Confirm('Se vaciará tu carrito.\n¿Estás seguro?', (response)=>{
                if(response){
                    $store.cart.clear();
                    $store.cartOpened = false;
                }
            });
        ">Vaciar Carrito</button>
        <button x-show="$store.cart.length() === 0" disabled class="bg-gray text-white py-1 px-2 text-sm rounded opacity-80 w-[48%] md:w-auto">Vaciar Carrito</button>

        <template x-if="$store.productsLength > 0">
            <div class="text-center order-first md:order-none w-full md:w-auto">
                <span class="font-semibold">TOTAL: </span>
                <span x-text="$store.priceFormat($store.productService.cartTotal())"></span>
            </div>
        </template>

        {{--BOTON Finalizar pedido--}}
        <button x-show="$store.cart.length() === 0" @click="$store.gotoCheckout('{{ route("checkout") }}')" class="bg-gray cursor-default text-white py-1 px-2 text-sm rounded opacity-80 w-[48%] md:w-auto">Ir a Finalizar Pedido</button>
        <button x-show="$store.cart.length() > 0" @click="$store.gotoCheckout('{{ route("checkout") }}')" class="bg-green text-white py-1 px-2 text-sm rounded hover:opacity-80 w-[48%] md:w-auto">Ir a Finalizar Pedido</button>
    </div>
</div>

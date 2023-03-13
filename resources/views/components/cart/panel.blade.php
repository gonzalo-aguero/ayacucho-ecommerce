<div class="w-full h-full flex flex-col" x-model="$store.products">
    <h2 class="text-center font-bold text-lg uppercase text-orange mb-2">Tu carrito</h2>
    <div class="grid grid-cols-cart-table grid-flow-row gap-2 text-xs font-semibold bg-gray-light-transparent">
        <span></span>
        <span>Producto</span>
        <span>Precio</span>
        <span>Unidades</span>
        <span>Subtotal</span>
    </div>
    <ul class="h-full w-full overflow-auto gap-4 grid items-start">
        <template x-if="$store.productsLength > 0 && $store.cart.content.length > 0">
            <template x-for="item in $store.cart.content">
                <li x-data="{
                        units: 1,
                        product: 0,
                        variation: ''
                    }" x-init=" product = $store.products[item.pos]; units = item.units;"
                    class="grid grid-cols-cart-table grid-flow-row gap-2 text-sm items-center">
                    <button class="hover:opacity-80" @click="
                        if($store.Confirm(`Se eliminará ${product.name} de tu carrito.\n¿Estás seguro?`)){
                            console.log('NO ELIMINADO (falta programar)');
                        }
                    ">
                        <img src="{{ asset('images/UI-Icons/icons8-remove-48.png') }}" class="h-4"/>
                    </button>
                    <span x-text="product.name + variation" class="truncate hover:text-clip"></span>
                    <span x-text="$store.priceFormat(product.price)"></span>
                    <input type="number" min="1" x-model.lazy="units" x-init="
                        $watch('units', (value, valueBef) => {
                            if(value === '') units = valueBef;
                            else{
                                item.units = value;
                                $store.cart.save();
                                console.log($store.cart.content);
                            }
                        });
                        $watch('item.units', value => units = value);
                    " class="w-16 bg-gray-light-transparent border border-gray-light2"/>
                    <span x-text="$store.priceFormat(product.price * Number.parseInt(units))"></span>
                </li>
            </template>
        </template>
    </ul>
    <div class="flex justify-between">
        <button class="bg-red text-white py-1 px-2 text-sm rounded hover:opacity-80" @click="
            if($store.Confirm('Se vaciará tu carrito.\n¿Estás seguro?')){
                $store.cart.clear()
            }
        ">Vaciar Carrito</button>
        <div>
            <span class="font-semibold">TOTAL: </span>
            <span x-text="$store.priceFormat($store.cart.total())"></span>
        </div>
        <a href="#" class="bg-green text-white py-1 px-2 text-sm rounded hover:opacity-80">Ir a Finalizar Pedido</a>
    </div>
</div>

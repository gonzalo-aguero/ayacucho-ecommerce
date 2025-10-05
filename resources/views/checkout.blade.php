@props([
    'pageTitle' => "Checkout - " . config('app.name'),
    "noIndex" => true,


    "sectionStyle" => "flex flex-wrap items-start w-72 border border-gray-light2 rounded p-4 w-full md-600:w-[30rem]",
    "sectionTitleStyle" => "font-semibold text-md w-full",
    "options1" => [
        0 => ["title"=>"Seleccionar", "value"=>null, "selected"=>true],
    ],
    "options2" => [
        0 => ["title"=>"Seleccionar", "value"=>null, "selected"=>true]
    ],
    "noteStyle" => "border border-gray-light2 bg-gray-light-transparent rounded p-2 w-full text-sm",
    "orderSummaryItemsStyles" => "flex justify-between bg-white py-2 px-1",
])
<x-store-layout pageTitle="{{$pageTitle}}" :$noIndex>
    <div class="relative flex flex-col items-center mt-40 mb-20 px-2 py-10 gap-8">
        <form
            method="POST"
            action="{{ route('order-create') }}"
                class="bg-white rounded-lg p-8 flex flex-wrap gap-6 justify-center items-start w-[95%] md:w-4/5"
            >
            <h2 class="w-full text-center uppercase text-2xl font-semibold drop-shadow-2xl">Checkout</h2>

            @csrf
            {{--CUSTOMER DETAIL SECTION--}}
            <div class="{{$sectionStyle}}">
                <h2 class="{{$sectionTitleStyle}}">DATOS DEL CLIENTE</h2>
                <x-form.input type="text" name="name" min="3" max="50" required>Nombre completo</x-form.input>
                <x-form.input type="text" name="dni" min="3" max="12" required>DNI</x-form.input>
                <x-form.input type="email" name="email" min="5" max="50">Correo electrónico</x-form.input>
                <x-form.input type="telephone" name="telephone" required>Teléfono o celular</x-form.input>
                <x-form.input type="text" name="city" min="2" max="50" required>Localidad</x-form.input>
                <x-form.input type="text" name="streetaddress" min="5" max="50" required>Dirección</x-form.input>
                <x-form.input type="text" name="note" max="250">Nota del pedido</x-form.input>
            </div>

            <div class="{{$sectionStyle}} border-none !p-0 flex-col gap-6">
                {{--PAYMENT METHOD SECTION--}}
                <div class="{{$sectionStyle}} h-min" x-data="paymentMethodSelect">
                    <h2 class="{{$sectionTitleStyle}}">MÉTODO DE PAGO</h2>
                    <x-form.input type="select" name="paymentMethod"
                        required
                        requiredSign="0"
                        :options="$options1"
                        getSelectedFrom="$store.paymentMethodService.methods"
                        saveSelectedIn="$store.paymentMethodService.selectedPaymentMethod"
                        >
                    </x-form.input>
                    <template x-if="undefined !== $store.paymentMethodService?.selectedPaymentMethod">
                        <p class="{{$noteStyle}}" x-text="$store.paymentMethodService?.selectedPaymentMethod?.note"></p>
                    </template>
                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('paymentMethodSelect', ()=>({
                                showDefaultOption: true,//set true to show the option "Select"
                                defaultOptionText: "Seleccionar",
                                showBladeOptions: false,
                                options: [],
                                init(){
                                    this.$watch('$store.displayPaymentMethods', (val) => {
                                        if(val){
                                            this.options = Alpine.store("paymentMethodService").getTexts();
                                        }
                                    });
                                }
                            }));
                        });
                    </script>
                    @error("_paymentMethod")
                        <div class="text-red text-xs my-1">Ha ocurrido un error al seleccionar el método de pago.</div>
                    @enderror
                </div>

                {{--SHIPPING ZONE SECTION--}}
                <div class="{{$sectionStyle}} h-min" x-data="shippingZoneSelect">
                    <h2 class="{{$sectionTitleStyle}}">ENTREGA</h2>
                    <x-form.input type="select" name="shippingZone"
                        required
                        requiredSign="0"
                        :options="$options2"
                        getSelectedFrom="$store.shippingZoneService.zones"
                        saveSelectedIn="$store.shippingZoneService.selectedShippingZone"
                        >
                    </x-form.input>
                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('shippingZoneSelect', ()=>({
                                showDefaultOption: true,//set true to show the option "Select"
                                defaultOptionText: "Seleccionar",
                                showBladeOptions: false,
                                options: [],
                                init() {
                                    this.$watch('$store.displayShippingZones', (val) => {
                                        if(val){
                                            this.options = Alpine.store("shippingZoneService").getTexts();
                                        }
                                    });
                                }
                            }));
                        });
                    </script>
                    @error("_shippingZone")
                        <div class="text-red text-xs my-1">Ha ocurrido un error al seleccionar la entrega.</div>
                    @enderror
                </div>
            </div>

            {{--ORDER SUMMARY--}}
            <template x-if="$store.showSummary">
                <div class="{{$sectionStyle}}">
                    <h2 class="{{$sectionTitleStyle}}">RESUMEN DEL PEDIDO</h2>
                    <ul class="flex flex-col w-full gap-[1px] mt-2 bg-gray-light2" x-data="{
                            cartTotal: $store.priceFormat($store.productService.cartTotal()),
                            shippingZone: 'Debe seleccionar',
                            shippingCost: $store.priceFormat(0),
                            paymentMethod: 'Debe seleccionar',
                            orderTotal: $store.priceFormat($store.order.total()),
                            updateOrderTotal(){
                                this.orderTotal = $store.priceFormat($store.order.total());
                            },
                            init() {
                                $watch('$store.cart', () => {
                                    this.cartTotal = $store.priceFormat($store.productService.cartTotal());
                                    this.updateOrderTotal();
                                });
                                $watch('$store.shippingZoneService?.selectedShippingZone', (val) => {
                                    // 'val' corresponds to the selected shipping zone object.
                                    this.shippingZone = val.name;
                                    this.shippingCost = $store.priceFormat(val.cost);
                                    this.updateOrderTotal();
                                });
                                $watch('$store.paymentMethodService?.selectedPaymentMethod', (val) => {
                                    // 'val' corresponds to the selected payment method object.
                                    const text = Alpine.store('paymentMethodService').getText(val);
                                    this.paymentMethod = text;
                                    this.updateOrderTotal();
                                });
                            }
                        }">
                        <li class="{{ $orderSummaryItemsStyles }}"><span>Total del carrito:</span><span x-text="cartTotal" class="text-right"></span></li>
                        <li class="{{ $orderSummaryItemsStyles }}"><span>Entrega: </span><span x-text="shippingZone" class="text-right"></span></li>
                        <li class="{{ $orderSummaryItemsStyles }}"><span>Costo de envío:</span><span x-text="shippingCost" class="text-right"></span></li>
                        <li class="{{ $orderSummaryItemsStyles }}"><span>Método de pago:</span><span x-text="paymentMethod" class="text-right"></span></li>
                        <li class="{{ $orderSummaryItemsStyles }} font-bold"><span>Total a pagar:</span><span x-text="orderTotal" class="text-right"></span></li>
                        <input type="hidden" x-model="shippingZone" name="_shippingZone"/>
                        <input type="hidden" x-model="paymentMethod" name="_paymentMethod"/>
                        <input type="hidden" x-model="cartTotal" name="cartTotal"/>
                        <input type="hidden" x-model="shippingCost" name="shippingCost"/>
                        <input type="hidden" x-model="orderTotal" name="orderTotal"/>
                    </ul>
                </div>
            </template>
            <template x-if="$store.cart.length() > 0">
                <input type="submit" value="Finalizar Compra" class="bg-green text-white py-2 px-8 rounded text-lg cursor-pointer w-full hover:opacity-80">
            </template>
            <template x-if="$store.cart.length() == 0">
                <input type="submit" value="Finalizar Compra" class="bg-gray text-white py-2 px-8 rounded text-lg cursor-default w-full opacity-80" disabled>
            </template>
            <span class="text-sm font-light text-black w-full md-600:w-4/5 md:w-2/3 lg:w-1/2 text-center">Serás redirigido al chat de WhatsApp de la empresa. Una vez allí, por favor envía el mensaje generado.</span>
        </form>
    </div>
</x-store-layout>

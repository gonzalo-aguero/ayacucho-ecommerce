<div x-data="{
        loadingProducts: ()=>{
            return $store.productsToPrint.length == 0;
        }
    }" class="flex flex-col items-center justify-center w-full sm:w-5/6 bg-white rounded-md shadow-2xl">
    <template x-for="category in $store.productsToPrint">
        <div x-data="{ open: true }" class="block w-full shadow">
            <h2 x-text="category.category" class="text-center py-3 text-xl uppercase cursor-pointer text-orange-medium font-bold
                hover:bg-gray-light rounded-md hover:underline decoration-orange-medium underline-offset-2" @click="open = !open"></h2>
            <div class="flex gap-2 justify-left w-full overflow-auto p-2 dropdown_menu-6 bg-gray-light" x-show="open">
                <template x-for="product in category.products">
                    {{-----------------------
                        Product card template
                    ---------------------------}}
                    <div class="bg-gray-light text-black w-40 rounded shadow-lg border-t-0 border border-gray-light-transparent shrink-0 relative"
                        x-data="{
                            units: 1,
                            get squareMeters() {
                                if(this.units >= 0) return (this.units * product.m2ByUnit).toPrecision(3);
                                else return 0;
                            },
                            defaultImage(){
                                const image =
                                    product.image !== null
                                    ? '{{ asset('images/products') }}/' + product.id + '.' + product.image
                                    : '{{ asset('images/defaultImage.svg') }}';
                                return image;
                            },
                            {{-- Measurable per square meter--}}
                            squareMeter: (product.m2Price != null && product.m2ByUnit != null),
                            addToCart(){
                                if($store.cart.add(product, this.units)){
                                    $store.Notify.Success('Agregado al carrito', 1500);
                                    this.units = 1;
                                }else{
                                    $store.Notify.Error('Ha ocurrido un error al agregar al carrito', 2000);
                                }
                            }
                        }">
                        {{--"NO STOCK" SIGN--}}
                        <template x-if="product.units == 0">
                            <div class="bg-red text-white text-center rounded-t absolute w-full opacity-80">SIN STOCK</div>
                        </template>
                        {{--PRODUCT NAME--}}
                        <div class="shrink-0 mb-2">
                            <a :href="$store.StaticProduct.productPage(product)">
                                <img class="h-40 w-full" :src="defaultImage" :alt="product.name" :title="product.description">
                            </a>
                        </div>
                        <h3 class="text-center text-sm font-medium mb-1"><a :href="$store.StaticProduct.productPage(product)" x-text="product.name"></a></h3>
                        <div class="text-center font-light flex-col items-center">
                            <!-- Primary price -->
                            <span class="text-base" x-text="'$' + product.price"></span>
                            <!-- Secondary price -->
                            <span class="text-xs" x-text="'$' + product.m2Price + '/m²'" x-show="squareMeter"></span>
                        </div>
                        <!-- Add to cart functionalities -->
                        <div class="flex flex-col w-full items-center pt-2 pb-3">
                            <div class="flex w-full justify-center items-center gap-1">
                                <input type="number" min="1" x-model="units" class="block w-12 text-sm rounded border border-gray-light2 text-center"/>
                                <span class="text-xs font-light">Unidades</span>
                            </div>
                            <div class="text-xs font-normal" x-show="squareMeter">
                                <span class="text-sm">=</span>
                                <span x-text="squareMeters"></span>
                                <span>m²</span>
                            </div>
                            <button class="bg-orange-light text-white text-sm p-1 mt-2 rounded active:opacity-80 hover:opacity-80
                                active:scale-95	"
                                x-on:click="addToCart">Añadir al carrito</button>
                        </div>
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

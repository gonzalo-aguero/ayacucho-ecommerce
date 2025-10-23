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
                    <x-product.product-card :closeModalOnAction="false" />
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

<div class="bg-gray-light-transparent w-full h-full flex flex-col" x-model="$store.products">
    <h2 class="text-center font-semibold">Tu carrito</h2>
    <ul class="h-full overflow-auto">
        <template x-for="item in $store.cart.content">
            <li x-data="{
                    product: false
                    }" x-effect="$nextTick(() => { product = $store.products[item.pos] })">
                <button>x</button>
                <span x-text="product.name"></span>
            </li>
        </template>
    </ul>
</div>

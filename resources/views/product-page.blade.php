@props([
    'pageTitle' => $product->name. " - " . config('app.name'),
    {{--"sectionStyle" => "flex flex-wrap items-start w-72 border border-gray-light2 rounded p-4 w-full md-600:w-[30rem]",--}}
    {{--"sectionTitleStyle" => "font-semibold text-md w-full",--}}
    {{--"options1" => [--}}
        {{--0 => ["title"=>"Seleccionar", "value"=>null, "selected"=>true],--}}
    {{--],--}}
    {{--"options2" => [--}}
        {{--0 => ["title"=>"Seleccionar", "value"=>null, "selected"=>true]--}}
    {{--],--}}
    {{--"noteStyle" => "border border-gray-light2 bg-gray-light-transparent rounded p-2 w-full text-sm",--}}
    {{--"orderSummaryItemsStyles" => "flex justify-between bg-white py-2 px-1",--}}
    {{--"headTags" => [--}}
        {{--'<meta name="robots" content="noindex">'--}}
    {{--]--}}
    "javascriptData" => [
        ["key" => "product", "value" => $product]
    ],
    {{--Measurable per square meter--}}
    "squareMeter" => $product->m2Price != null && $product->m2ByUnit != null
])
<x-store-layout pageTitle="{{$pageTitle}}" isProductPage="true" :$javascriptData>
    <div class="relative flex flex-col items-center mt-40 mb-20 px-4 py-10 bg-white"
         x-data="{
            units: 1,
            get squareMeters() {
                if(this.units >= 0) return (this.units * product.m2ByUnit).toPrecision(3);
                else return 0;
            },
        }">
    <div class="relative">
        {{--"NO STOCK" SIGN--}}
        @if($product->units == 0)
            <div class="bg-red text-white text-center rounded-t absolute w-full opacity-80">SIN STOCK</div>
        @endif

        <img
            src="{{
                $product->image != null && $product->image != ""
                ? asset('images/products/'. $product->id . "." . $product->image)
                : asset('images/defaultImage.svg');
            }}"
            class="w-80 h-80 object-cover"
            alt="{{ $product->name }}" title="{{ $product->description }}"
        >
    </div>
    <h1 class="text-2xl font-semibold my-2">{{ $product->name }}</h1>

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
            <input type="number" min="1" x-model="units" :disabled="product.units == 0"
                class="block w-16 text-xl font-light rounded border border-gray-light2 text-center"/>
            <span class="text-base font-light">Unidades</span>
        </div>
        @if($squareMeter)
            <div class="text-base font-normal text-center">
                <span class="text-lg">=</span>
                <span x-text="$store.StaticProduct.squareMeters(units, product)"></span>
                <span>m²</span>
            </div>
        @endif
        <button class="text-white text-xl p-2 mt-2 rounded"
            :class=" product.units == 0
                ? 'bg-gray opacity-80'
                : 'bg-orange-light active:opacity-80 hover:opacity-80 active:scale-95'"
                x-on:click="$store.StaticProduct.addToCart(units, product)"
            :disabled="product.units == 0"
            >Añadir al carrito</button>
    </div>

    {{--More info section--}}
    <div class="border border-gray-light2 bg-gray-light-transparent rounded-md p-3">
        <h2 class="text-lg font-medium uppercase mt-2">Descripción</h2>
        <p>{{ $product->description }}</p>
        <h2 class="text-lg font-medium uppercase mt-2">Categoría</h2>
        <p>{{ $product->category }}</p>
    </div>
    {{--{#387 ▼ // resources/views/product-page.blade.php--}}
      {{--+"units": 35--}}
      {{--+"showUnits": false--}}
      {{--+"m2ByUnit": 2.3--}}
      {{--+"variationID": null--}}
    {{--}--}}
</div>
</x-store-layout>

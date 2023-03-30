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
])
<x-store-layout pageTitle="{{$pageTitle}}" isProductPage="true">
    <div class="relative flex flex-col items-center mt-40 mb-20 px-4 py-10 gap-8 bg-white">
        <div class="">
            <img
                src="{{ asset('images/products/'. $product->id . "." . $product->image) }}"
                class="w-80 h-80 object-cover"
            >
        </div>
        <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>

        <div class="border border-gray-light2 bg-gray-light-transparent rounded-md p-3">
            <h2 class="text-lg font-medium uppercase mb-1">Descripción</h2>
            <p class="">{{ $product->description }}</p>
        </div>
        {{--{#387 ▼ // resources/views/product-page.blade.php--}}
          {{--+"id": "38101"--}}
          {{--+"name": "REVESTIMIENTO"--}}
          {{--+"price": 5156.55--}}
          {{--+"m2Price": 2241.98--}}
          {{--+"description": "REVEST. IBIZA BLANCO 32 X 47 CM. 1� CAL. 2.30 M2 " CAÑ�UELAS ""--}}
          {{--+"image": "PNG"--}}
          {{--+"thumbnail": null--}}
          {{--+"category": "CERÁMICO"--}}
          {{--+"units": 35--}}
          {{--+"showUnits": false--}}
          {{--+"m2ByUnit": 2.3--}}
          {{--+"variationID": null--}}
        {{--}--}}
    </div>
</x-store-layout>

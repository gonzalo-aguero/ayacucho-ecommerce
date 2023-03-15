@props([
    'pageTitle' => "Checkout - " . config('app.name'),
    "sectionStyle" => "flex flex-wrap items-start w-72 border border-gray-light2 rounded p-4 w-[30rem]",
    "sectionTitleStyle" => "font-semibold text-md",
    "options1" => [
        0 => ["title"=>"Seleccionar", "value"=>null, "selected"=>true]
    ],
    "options2" => [
        0 => ["title"=>"Seleccionar", "value"=>null,"selected"=>true],
        1 => ["title"=>"Retiro en depósito", "value"=>"1","selected"=>false],
        2 => ["title"=>"Envío a domicilio", "value"=>"2","selected"=>false]
    ],
    "noteStyle" => "border border-gray-light2 bg-gray-light-transparent rounded p-2 w-full text-sm"
])
<x-store-layout pageTitle="{{$pageTitle}}">
    <div class="relative flex flex-col items-center mt-40 mb-20 px-2 py-10 gap-8">
        <form
            method="POST"
            action="{{ route('order-create') }}"
            class="bg-white rounded-lg p-8 flex flex-wrap gap-6 justify-between w-4/5"
            >
            <h2 class="w-full text-center uppercase text-2xl font-semibold drop-shadow-2xl">Checkout</h2>
            @csrf
            {{--CUSTOMER DETAIL SECTION--}}
            <div class="{{$sectionStyle}}">
                <h2 class="{{$sectionTitleStyle}}">DATOS DEL CLIENTE</h2>
                <x-form.input type="text" name="name" min="3" max="75" required>Nombre completo</x-form.input>
                <x-form.input type="email" name="email" min="5" max="75" required>Correo Electrónico</x-form.input>
                <x-form.input type="telephone" name="telephone" required>Teléfono o Celular</x-form.input>
                <x-form.input type="text" name="city" min="2" max="75" required>Localidad</x-form.input>
                <x-form.input type="text" name="Street address" min="3" max="75" required>Dirección</x-form.input>
                <x-form.input type="text" name="note" max="250">Nota del pedido</x-form.input>
            </div>

            <div class="{{$sectionStyle}} border-none p-0 flex-col gap-6">
                {{--PAYMENT METHOD SECTION--}}
                <div class="{{$sectionStyle}} h-min">
                    <h2 class="{{$sectionTitleStyle}}">MÉTODO DE PAGO</h2>
                    <x-form.input type="select" name="paymentMethod" required requiredSign="0" :options="$options1"></x-form.input>
                    <p class="{{$noteStyle}}">Some optional text here...</p>
                </div>
                {{--PAYMENT METHOD SECTION--}}
                <div class="{{$sectionStyle}} h-min">
                    <h2 class="{{$sectionTitleStyle}}">ENTREGA</h2>
                    <x-form.input type="select" name="paymentMethod" required requiredSign="0" :options="$options2"></x-form.input>
                    <p class="{{$noteStyle}}">Some optional text here...</p>
                </div>
            </div>

            <input type="submit" value="Finalizar Compra" class="bg-green text-white py-2 px-8 rounded text-lg cursor-pointer w-full hover:opacity-80"/>
        </form>
    </div>
</x-store-layout>

@props([
    'pageTitle' => config('app.name'),
    "pageDescription" => "Venta de Cerámicos, Ceramicos, Porcelanatos, Aberturas, Ventanas, Sanitarios, Griferías de Baño y cocina,etc.",
    "keywords" => "Cerámicos, Porcelanato, Aberturas, Ventanas, Sanitarios, Griferias de baño, Griferias, cocina, Griferias de cocina, Revestimientos, Porcelanico, Adhesivos, Pastinas"
])
<x-store-layout
    pageTitle="{{$pageTitle}}"
    :$pageDescription
    :$keywords
    :revisit="true"
    >
    <div class="relative flex flex-col items-center mt-20 mb-16 px-2 py-10 gap-8 bg-[url('{{ asset('images/slide.jpeg')  }}')]">
        <h2 class="uppercase text-2xl text-white font-semibold drop-shadow-2xl">Nuestros Productos</h2>
        <x-categories-menu/>
        <x-google-reviews/>
    </div>
</x-store-layout>

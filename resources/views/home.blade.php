@props(['pageTitle' => config('app.name')])
<x-store-layout pageTitle="{{$pageTitle}}">
    <div class="relative flex flex-col items-center mt-20 mb-16 px-2 py-10 gap-8 bg-[url('{{ asset('images/slide.jpeg')  }}')]">
        <h2 class="uppercase text-2xl text-white font-semibold drop-shadow-2xl">Nuestros Productos</h2>
        <x-categories-menu/>
    </div>
</x-store-layout>

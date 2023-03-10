<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <!-- Styles -->
        @livewireStyles
        <script>
            const DEBUG = {{ $DEBUG == true ? "true" : "false" }};
        </script>
        @vite(['resources/css/app.css', 'resources/css/Notification-Bar.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <body class="antialiased">
        <x-header></x-header>
        <div id="background" class="fixed z-[-1] top-0 left-0 h-full w-full bg-black select-none">
            <img src="{{ asset('images/background.jpeg') }}" class="absolute top-0 left-0 w-full blur-[3px]"/>
            <div class="absolute top-0 left-0 w-full h-full bg-black/50"></div>
        </div>
        {{--Content--}}
        <div class="relative flex flex-col items-center mt-20 mb-16 px-2 py-10 gap-8 bg-[url('{{ asset('images/slide.jpeg')  }}')]">
            <h2 class="uppercase text-2xl text-white font-semibold drop-shadow-2xl">Nuestros Productos</h2>
            <x-categories-menu/>
        </div>
        <x-footer></x-footer>
        @livewireScripts
    </body>
</html>

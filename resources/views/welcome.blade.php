<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>RH Renova tu Hogar</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <!-- Styles -->
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <body class="antialiased">
        <x-header></x-header>
        <div class="relative flex flex-col items-center min-h-screen px-2 py-10 gap-8 bg-gray-light-transparent">
            <h2 class="uppercase text-2xl">Nuestros Productos</h2>
            <x-categories-menu/>
        </div>
        @livewireScripts
    </body>
</html>

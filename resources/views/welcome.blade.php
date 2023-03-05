<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>RH Renova tu Hogar</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased" x-data="{
            Products: []
        }">
        <div class="relative flex flex-col justify-center items-center min-h-screen p-2">
            @yield('product', view("product.card"))
            <livewire:product-list />
            <x-categories-menu/>
        </div>
        @livewireScripts
    </body>
</html>

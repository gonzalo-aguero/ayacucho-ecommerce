<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>RH Renova tu Hogar</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <!-- Styles -->
        @vite(['resources/css/app.css'])
    </head>
    <body class="antialiased">
        {{--<x-header></x-header>--}}
        <div class="relative flex flex-col items-center justify-center min-h-screen px-2 py-10 bg-gray-light-transparent">
            {{--<h2 class="uppercase text-2xl">Nuestros Productos</h2>--}}
            {{--<x-categories-menu/>--}}
            <h1 class="text-gray text-2xl mb-5">503 | SERVICE UNAVAILABLE</h1>
            <h2 class="text-gray text-lg underline">SITIO WEB EN MANTENIMIENTO</h2>
            <p class="text-center font-extralight">Lo sentimos, estamos realizando tareas de mantenimiento en nuestro sitio web,
                <br>por lo que no está disponible en este momento. Vuelva a intentar más tarde.</p>
        </div>
    </body>
</html>

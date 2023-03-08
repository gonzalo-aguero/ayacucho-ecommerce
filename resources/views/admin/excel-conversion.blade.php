<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>RH Renova tu Hogar</title>

        <!-- Fonts -->
        {{--<link rel="stylesheet" href="https://rsms.me/inter/inter.css">--}}
        <!-- Styles -->
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/admin.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased" x-ref="body">
        <div class="relative flex flex-col justify-center items-center min-h-screen p-2" x-data="{
                loading: false,
                process(e){
                    this.loading = true;
                    document.body.classList.add('cursor-progress');
                    document.getElementById('button').classList.add('cursor-progress', 'animate-pulse');
                    $store.convert(e);
                    this.loading = false;
                    document.body.classList.remove('cursor-progress');
                    document.getElementById('button').classList.add('cursor-progress', 'animate-pulse');
                }
            }">
            <h2 class="text-2xl font-bold">Software de conversión de Excel a JSON</h2>
            <p>Una vez que haya subido el archivo <strong>.xlsx</strong> a la carpeta especificada por el desarrollador, presione en "Iniciar Conversión".</p>
            <form id="ajax-form">
                @csrf
                <button id="button" x-text="loading ? 'Procesando...' : 'Iniciar Conversión'" @click="process" class="bg-green text-white p-2.5 rounded mt-5 active:opacity-80"></butto>
            {{--</form>--}}
        </div>
        @livewireScripts
    </body>
</html>

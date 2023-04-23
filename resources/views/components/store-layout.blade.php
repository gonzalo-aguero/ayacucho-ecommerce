@aware([
    'pageTitle' => config('app.name'),
    "pageDescription" => "",
    "keywords" => "",
    "noIndex" => false,
    "revisit" => false,

    "isProductPage" => false,
    "javascriptData" => []
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="content-type" charset="text/html; utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>{{ $pageTitle }}</title>
        @if($noIndex)
            <meta name="robots" content="noindex">
        @endif
        @if($revisit)
            <meta name="revisit" content="5 days">
        @endif
        <meta name="description" content="{{ $pageDescription }}">
        <meta name="keywords" content="{{ $keywords }}"/>
        <meta http-equiv="expires" content="432000"/>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css"/>
        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <script>
            const DEBUG = {{ config('app.debug') ? "true" : "false" }};
            const IS_PRODUCT_PAGE = {{ $isProductPage ? "true" : "false" }};
            @foreach ($javascriptData as $data)
                const {{ $data["key"] }} = {{  Js::from($data["value"]) }};
            @endforeach
        </script>
        @vite(['resources/css/app.css', 'resources/css/Notification-Bar.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased scroll-smooth" x-init="">
        {{--Header--}}
        <x-header></x-header>

        {{--Cart black-transparent background--}}
        <div class="fixed z-10 left-0 top-0 bg-black/30 w-screen h-screen animate__animated very_fast_animation"
            x-cloak
            x-show="$store.cartOpened"
            x-transition:enter="animate__fadeIn"
            x-transition:leave="animate__fadeOut"
        ></div>
        {{--Cart Container--}}
        <div x-cloak x-show="$store.cartOpened"
            class="fixed bg-white top-1/8 md-820:top-1/4 right-[2.5%] md-820:right-0 w-[95%] md-820:w-[800px] h-3/4 md-820:h-1/2 z-10 py-4 px-4 shadow-lg rounded-lg md:rounded-r-none animate__animated cart_container"
            x-transition:enter="animate__fadeInRightBig"
            x-transition:leave="animate__fadeOutRightBig"
            @click.outside="!$store.ConfirmVisible ? $store.cartOpened = false : ''"
            >
            <x-cart.panel></x-cart.panel>
        </div>

        {{--Background image--}}
        <div id="background" class="fixed z-[-1] top-0 left-0 h-full w-full bg-black select-none object-cover">
            <img src="{{ asset('images/background.webp') }}" class="absolute top-0 left-0 blur-[3px] h-full w-auto lg:w-full object-cover"/>
            <div class="absolute top-0 left-0 w-full h-full bg-black/50"></div>
        </div>

        {{--Content--}}
        {{ $slot }}

        {{--Footer--}}
        <x-footer></x-footer>

        {{--Auxiliary black-transparent background--}}
        <div id="aux_black_transparent_bg" class="hidden z-10 left-0 top-0 bg-black/30 w-screen h-screen animate__animated very_fast_animation"
            x-cloak
            x-transition:enter="animate__fadeIn"
            x-transition:leave="animate__fadeOut"
        ></div>
        <div id="confirm_modal" class="hidden z-10 shadow bg-white rounded-lg
                left-[5%] top-[35%] w-[90%] h-[30%]
                md-600:w-[50%] md-600:left-[25%]
                md-820:w-[40%] md-820:left-[30%]">
            <div class="flex flex-col p-4 justify-between h-full">
                <p class="text-lg text-center"></p>
                <div class="flex justify-between">
                    <button class="cancel_btn border border-gray-light2 font-medium rounded p-2" name="Cancelar"></button>
                    <button class="confirm_btn border border-gray-light2 font-medium rounded p-2" name="Confirmar"></button>
                </div>
            </div>
        </div>
    </body>
</html>

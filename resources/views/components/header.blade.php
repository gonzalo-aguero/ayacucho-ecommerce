@props(['navItems' => [
        [
            'img' => asset('images/UI-Icons/icons8-home-page-48.png'),
            'text' => "Inicio",
            'url' => "href=".route('home'),
            'action' => '',
        ],
        [
            'img' => asset('images/UI-Icons/icons8-search-50.png'),
            'text' => "Buscar",
            'url' => '',
            'action' => 'toggleSearch',
        ],
        [
            'img' => asset('images/UI-Icons/icons8-shopping-cart-48.png'),
            'text' => "Carrito",
            'url' => '',
            'action' => 'toggleCart',
        ],
        [
            'img' => asset('images/UI-Icons/icons8-checkout-48.png'),
            'text' => "Checkout",
            'url' => '',
            'action' => '$store.gotoCheckout("'. route('checkout') .'")',
        ]

    ]
])
<header class="fixed top-0 left-0 z-10 w-screen flex flex-nowrap justify-between md:justify-evenly px-5 md:px-0 items-center shadow-md bg-white" x-data="{
        smartphone: window.screen.width < 768,
        menuOpened: false || window.screen.width >= 768,
        toggleCart(){
            if(this.smartphone) this.menuOpened = false;
            Alpine.store('cartOpened', !$store.cartOpened);
        },
        toggleSearch(){
            if(this.smartphone) this.menuOpened = false;
            Alpine.store('searchModalOpened', !$store.searchModalOpened);
        },
        toggleMenu(){
            this.menuOpened = !this.menuOpened;
        },
        openMenu(){
            this.menuOpened = true;
        }
    }" @keydown.ctrl.k.window.prevent="$store.searchModalOpened = true">
    {{--Logo and Title--}}
    <div class="flex flex-nowrap justify-between items-center gap-2 py-3 px-2 rounded-sm">
        <a href="{{ route('home') }}"><img src="{{asset('favicon-96x96.png')}}" alt="Logo {{ config('app.name') }}" title="Logo {{ config('app.name') }}" class="h-16"></a>
        <a href="{{ route('home') }}">
            <h1 class="font-bold uppercase ">
                <span class="text-black">{{ config('app.name_1') }}</span>
                <span class="text-orange">{{ config('app.name_2') }}</span>
            </h1>
        </a>
    </div>
    <nav clas="text-orange h-full">
        <button name="Abrir Menu" @click="openMenu()" x-show="smartphone" x-cloak class="active:opacity-80 text-center">
            <img src="{{ asset('images/UI-Icons/icons8-menu-rounded-100.png') }}" class="h-12">
        </button>
        <ul class="fixed z-10 w-2/3 left-0 !top-0 pt-[5.4rem] shadow-md h-full gap-0
            md:relative flex flex-col md:flex-row md:flex-nowrap md:h-16 md:w-auto md:left-auto md:top-auto md:shadow-none
            py-1 px-2 md:pt-1 bg-white text-orange animate__animated very_fast_animation"
            x-show="!smartphone || menuOpened"
            x-cloak @click.outside="toggleMenu"
            x-transition:enter="animate__fadeInLeftBig"
            x-transition:leave="animate__fadeOutLeftBig"
        >
            @foreach($navItems as $item)
                <li class="flex flex-nowrap items-center gap-1 font-base cursor-pointer hover:bg-gray-light" x-on:click="{{ $item["action"] }}">
                    <a {{ $item["url"] }} class="flex flex-nowrap items-center h-full gap-2 py-10 md:py-2 px-8">
                        <img src="{{ $item["img"] }}" class="h-6 w-6 shrink" alt="{{ $item["text"] }}"/>
                        <span class="text-lg md:text-base">{{ $item["text"] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="fixed z-0 left-0 top-0 bg-black/50 w-screen h-screen animate__animated very_fast_animation"
            x-cloak
            x-show="smartphone && menuOpened"
            x-transition:enter="animate__fadeIn"
            x-transition:leave="animate__fadeOut"
        ></div>
    </nav>
</header>

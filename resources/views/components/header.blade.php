@props(['navItems' => [
        [
            'img' => asset('images/icons8-home-page-48.png'),
            'text' => "Inicio",
            'url' => "href=".route('home'),
            'action' => '',
        ],
        [
            'img' => asset('images/icons8-shopping-cart-48.png'),
            'text' => "Carrito",
            'url' => '',
            'action' => 'toggleCart',
        ],
        [
            'img' => asset('images/icons8-checkout-48.png'),
            'text' => "Checkout",
            'url' => "href=".route('checkout'),
            'action' => '',
        ]
    ]
])
<header class="fixed top-0 left-0 z-10 w-screen flex flex-nowrap justify-evenly items-center shadow-md bg-white" x-data="{
        cartOpen: false,
        toggleCart(){
            this.cartOpen = !this.cartOpen
        },
    }">
    {{--Logo and Title--}}
    <div class="flex flex-nowrap justify-between items-center gap-2 py-3 px-2">
        <a href="{{ route('home') }}"><img src="{{asset('logo.png')}}" alt="Logo {{ config('app.name') }}" title="Logo {{ config('app.name') }}" class="h-16"></a>
        <a href="{{ route('home') }}"><h1 class="font-bold uppercase"><span class="text-black">{{ config('app.name_1')  }}</span><span class="text-orange">{{ config('app.name_2')  }}</span></h1></a>
    </div>
    <nav clas="text-orange h-full">
        <ul class="text-orange flex flex-nowrap py-1 px-2 h-16">
            @foreach($navItems as $item)
                <li class="flex flex-nowrap items-center gap-1 font-base py-2 px-8 cursor-pointer hover:bg-gray-light" x-on:click="{{ $item["action"] }}">
                    <a {{ $item["url"] }}><img src="{{ $item["img"] }}" class="h-6"/></a>
                    <a {{ $item["url"] }} class="text-base">{{ $item["text"] }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
    <div x-show="cartOpen">
        <x-cart.panel></x-cart.panel>
    </div>
</header>

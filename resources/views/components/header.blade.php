@props(['navItems' => [
        [
            'img' => asset('images/icons8-home-page-48.png'),
            'text' => "Inicio",
            'url' => "href=".route('home')
        ],
        [
            'img' => asset('images/icons8-shopping-cart-48.png'),
            'text' => "Carrito",
            'url' => ''
        ],
        [
            'img' => asset('images/icons8-checkout-48.png'),
            'text' => "Checkout",
            'url' => "href=".route('checkout')
        ]
    ]
])
<header class="flex flex-nowrap justify-evenly items-center shadow">
    {{--Logo and Title--}}
    <div class="flex flex-nowrap justify-between items-center gap-2 py-1 px-2">
        <a href="{{ route('home') }}"><img src="{{asset('logo.png')}}" alt="Logo RH Renová tu Hogar" title="Logo RH Renová tu Hogar" class="h-16"></a>
        <a href="{{ route('home') }}"><h1 class="font-bold uppercase"><span class="text-black">RH Renová</span><span class="text-orange"> tu Hogar</span> </h1></a>
    </div>
    <nav clas="text-orange h-full">
        <ul class="text-orange flex flex-nowrap py-1 px-2 h-16">
            @foreach($navItems as $item)
                <li class="flex flex-nowrap items-center gap-1 font-base py-2 px-8 cursor-pointer hover:bg-gray-light">
                    <a {{ $item["url"] }}><img src="{{ $item["img"] }}" class="h-6"/></a>
                    <a {{ $item["url"] }} class="text-base">{{ $item["text"] }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
</header>

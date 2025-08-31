<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RH Renova tu Hogar - Sitio en Mantenimiento</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <!-- Styles -->
        @vite(['resources/css/app.css'])
    </head>
    <body class="antialiased scroll-smooth">
        {{--Header--}}
        <header class="fixed top-0 left-0 z-10 w-screen flex flex-nowrap justify-between md:justify-evenly px-5 md:px-0 items-center shadow-md bg-white">
            {{--Logo and Title--}}
            <div class="flex flex-nowrap justify-between items-center gap-2 py-3 px-2">
                <a href="{{ route('home') }}">
                    <img src="{{asset('logo.webp')}}" alt="Logo {{ config('app.name') }}" title="Logo {{ config('app.name') }}" class="h-16">
                </a>
                <a href="{{ route('home') }}">
                    <h1 class="font-bold uppercase">
                        <span class="text-black">{{ config('app.name_1') }}</span>
                        <span class="text-orange">{{ config('app.name_2') }}</span>
                    </h1>
                </a>
            </div>
            <nav class="text-orange h-full">
                <ul class="flex flex-row flex-nowrap h-16 items-center gap-4">
                    <li class="flex flex-nowrap items-center gap-1 font-base cursor-pointer hover:bg-gray-light px-4 py-2 rounded">
                        <a href="https://wa.me/{{ config('company.phone_number_1') }}?text=Hola, necesito información sobre sus productos"
                           target="_blank"
                           class="flex flex-nowrap items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            <span class="text-lg">Contactar por WhatsApp</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        {{--Background image--}}
        <div id="background" class="fixed z-[-1] top-0 left-0 h-full w-full bg-black select-none object-cover">
            <img src="{{ asset('images/background.webp') }}" class="absolute top-0 left-0 blur-[3px] h-full w-auto lg:w-full object-cover"/>
            <div class="absolute top-0 left-0 w-full h-full bg-black/50"></div>
        </div>

        {{--Main Content--}}
        <main class="relative flex flex-col items-center justify-center min-h-screen px-4 py-20 mt-16">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 md:p-12 max-w-2xl mx-auto text-center">
                {{--Error Icon/Logo--}}
                <div class="mb-6">
                    <img src="{{asset('logo.webp')}}" alt="Logo {{ config('app.name') }}" class="h-20 mx-auto mb-4 opacity-80">
                </div>

                {{--Error Title--}}
                <h1 class="text-4xl md:text-5xl font-bold text-gray-dark mb-4">
                    <span class="text-orange">503</span> |
                    <span class="text-gray-dark">SERVICE UNAVAILABLE</span>
                </h1>

                {{--Error Subtitle--}}
                <h2 class="text-2xl md:text-3xl font-semibold text-orange mb-6 underline decoration-2 decoration-orange-light">
                    SITIO WEB EN MANTENIMIENTO
                </h2>

                {{--Error Message--}}
                <div class="space-y-4 text-gray-dark">
                    <p class="text-lg font-medium">
                        Lo sentimos, estamos realizando tareas de mantenimiento en nuestro sitio web.
                    </p>
                    <p class="text-base font-light">
                        Por favor, vuelva a intentar más tarde. Estamos trabajando para mejorar su experiencia.
                    </p>
                </div>

                                                {{--Action Button--}}
                <div class="mt-8">
                    <a href="https://wa.me/{{ config('company.phone_number_1') }}?text=Hola, necesito información sobre sus productos"
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-orange hover:bg-orange-medium text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        Contactar por WhatsApp
                    </a>
                </div>

                {{--Maintenance Info--}}
                {{-- <div class="mt-8 p-4 bg-gray-light rounded-lg border-l-4 border-orange">
                    <p class="text-sm text-gray-dark">
                        <strong>Tiempo estimado:</strong> 2-4 horas<br>
                        <strong>Horario de mantenimiento:</strong> {{ date('H:i') }} - {{ date('H:i', strtotime('+2 hours')) }}
                    </p>
                </div> --}}
            </div>
        </main>

        {{--Footer--}}
        <footer class="w-full p-4 py-8 bg-white footer-shadow flex flex-col items-center gap-4 mt-16">
            @if(config('company.address') != "")
                <div class="w-5/6 sm:w-96">
                    <h2 class="text-base text-orange font-medium">Dirección:</h2>
                    <p class="text-base font-light">{{ config('company.address') }}</p>
                </div>
            @endif
            @if(config('company.hours') != "")
                <div class="w-5/6 sm:w-96">
                    <h2 class="text-base text-orange font-medium">Horarios:</h2>
                    <p class="text-base font-light">{{ config('company.hours') }}</p>
                </div>
            @endif
            @if(config('company.phone_number_1') != null || config('company.phone_number_2') != null)
                <div class="w-5/6 sm:w-96">
                    <h2 class="text-base text-orange font-medium">Contacto:</h2>
                    @if(config('company.phone_number_1') != null)
                        <p class="text-base ml-2 font-light w-5/6">+{{ config('company.phone_number_1') }}</p>
                    @endif
                    @if(config('company.phone_number_2') != null)
                        <p class="text-base ml-2 font-light">+{{ config('company.phone_number_2') }}</p>
                    @endif
                </div>
            @endif
            <h2 class="text-orange-medium mt-4 text-base font-semibold text-center">&#169; {{ date('Y')." ".config('app.name') }}</h2>
        </footer>
    </body>
</html>

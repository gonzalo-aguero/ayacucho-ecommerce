<footer class="w-full p-4 py-8 bg-white footer-shadow flex flex-col items-center gap-4">
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

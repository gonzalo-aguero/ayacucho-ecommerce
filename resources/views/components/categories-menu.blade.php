<div class="flex  gap-2 justify-left w-full overflow-auto p-2">
    @for ($i = 0; $i < 12; $i++)
        <div class="bg-gray-light text-black w-40 rounded shadow-lg border-t-0 border border-gray-light-transparent shrink-0 select-none flex flex-col items-center">
            <div class="shrink-0 mb-2 animate-pulse">
                <img class="h-40 w-full" src="{{ asset('images/defaultImage.svg') }}" alt="Product Thumbnail">
            </div>
            <h3 class="mb-1 bg-gray-light2 h-5 w-32 mx-2 rounded animate-pulse"></h3>
            <div class="mb-1 bg-gray-light2 h-5 w-24 mx-2 rounded animate-pulse"></div>
            <!-- Add to cart functionalities -->
            <div class="flex flex-col w-full items-center pt-2 pb-3 gap-2 animate-pulse">
                <div class="mb-1 bg-gray-light2 h-5 w-20 mx-2 rounded"></div>
                <button class="bg-orange mb-1 h-5 w-20 mx-2 rounded opacity-80" disabled></button>
            </div>
        </div>
    @endfor
</div>

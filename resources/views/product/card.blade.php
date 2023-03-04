<div class="bg-gray-light text-black w-40 rounded shadow-lg border border-gray-light-transparent"
    x-data="{
        units: 1,
        get squareMeters() {
            if(this.units >= 0) return (this.units * 2.64).toPrecision(3);
            else return 0;
        }
    }">
    <div class="shrink-0 mb-2">
        <img class="h-40 w-full" src="{{ Vite::asset('resources/images/defaultImage.svg') }}" alt="Product Thumbnail">
    </div>
    <h3 class="text-center text-sm font-medium mb-1"><a>Cerámicos Marca Modelo x 2,64m2</a></h3>
    <div class="text-center font-light flex-col items-center">
        <!-- Primary price -->
        <span class="text-base">$2399</span>
        <!-- Secondary price -->
        <span class="text-xs">$904/m²</span>
    </div>
    <!-- Add to cart functionalities -->
    <div class="flex flex-col w-full items-center pt-2 pb-3">
        <div class="flex w-full justify-center items-center gap-1">
            <input type="number" min="1" x-model="units" class="block w-12 text-sm rounded border border-gray-light2 text-center" x-model="units"/>
            <span class="text-xs font-light">Unidades</span>
        </div>
        <div class="text-xs font-normal mb-2">
            <span class="text-sm">=</span>
            <span x-text="squareMeters"></span>
            <span>m²</span>
        </div>
        <button class="bg-orange text-white text-sm p-1 rounded active:opacity-80 hover:opacity-80">Añadir al carrito</button>
    </div>
</div>

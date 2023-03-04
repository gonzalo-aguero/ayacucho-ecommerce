<div wire:model="productsLoaded">
    {{-- Because she competes with no one, no one can compete with her. --}}
    @if($productsLoaded)
        {{--Render real products--}}
        {{--@foreach ($user->posts as $i => $post)--}}
        {{--@endforeach--}}
    @else
        {{--Render "loading products"--}}
        {{--@for ($i = 0; $i < 6; $i++)--}}
            {{--T--}}
        {{--@endfor--}}
    @endif
    Products Loaded: {{ $productsLoaded ? "SI" : "NO"}}
</div>

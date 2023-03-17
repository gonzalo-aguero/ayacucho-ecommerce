@props([
    "type" => "text",
    "name" => "input_". time(),
    "min" => "",
    "max" => "",
    "placeholder" => "",
    'required' => false,
    "requiredSign" => true,
    'inputStyle' => "bg-gray-light-transparent border border-gray-light2 rounded px-2",
    "is_valid" => "",
    "is_invalid"=> "border-red",
    "alpine_data" => ""
])
@aware([
    "options"=>[]
])
<div class="flex flex-col text-base w-full my-2">
    <label for="{{ $name }}">
        {{ $slot }}
        @if($required && $requiredSign)
            <span class="text-red font-bold">*</span>
        @endif
    </label>
    @if($type != "select" && $type != "textarea")
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            min="{{$min}}"
            max="{{$max}}"
            placeholder="{{$placeholder}}"
            {{ $required ? "required" : "" }}
            class="
                {{$inputStyle}}
                @error($name) {{$is_invalid}} @else {{$is_valid}} @enderror
            "
            value="{{ old($name) }}">
    @elseif($type == "select")
        <select
            name="{{ $name }}"
            placeholder="{{$placeholder}}"
            {{ $required ? "required" : "" }}
            class="
                {{$inputStyle}}
                @error($name) {{$is_invalid}} @else {{$is_valid}} @enderror
            "
            selected="{{ old($name) }}" x-data="{{$alpine_data}}" x-modelable="$data.selected">

            <template x-if="$data.showDefaultOption">
                    <option value="" selected x-text="$data.defaultOptionText"></option>
            </template>
            <template x-for="option in $data.options">
                <option :value="option.name" x-text="option.name"></option>
            </template>
            @foreach($options as $option)
                <template x-if="$data.showBladeOptions">
                    <option value="{{$option["value"]}}" {{ $option["selected"] ? "selected" : "" }}>{{$option["title"]}}</option>
                </template>
            @endforeach
        </select>
        <span x-text="$data.selected"></span>
    @endif
    @error($name)
        <div class="text-red text-xs my-1">{{ $message }}</div>
    @enderror
</div>

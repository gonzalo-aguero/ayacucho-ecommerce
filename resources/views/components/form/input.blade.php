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
            selected="{{ old($name) }}">

            @foreach($options as $option)
                <option value="{{$option["value"]}}" {{ $option["selected"] ? "selected" : "" }}>{{$option["title"]}}</option>
            @endforeach
        </select>
    @endif
    @error($name)
        <div class="text-red text-xs my-1">{{ $message }}</div>
    @enderror
</div>

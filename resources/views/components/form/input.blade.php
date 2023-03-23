@props([
    "type" => "text",
    "name" => "input_". time(),
    "min" => "",
    "max" => "",
    "placeholder" => "",
    'required' => false,
    "requiredSign" => true,
    'inputStyle' => "bg-gray-light-transparent border border-gray-light2 rounded px-2 w-full",
    "is_valid" => "",
    "is_invalid"=> "border-red",
    "saveSelectedIn" => "const saveSelectedTo_".time(),
    "getSelectedFrom" => "[]" //it's used to find the selected item data from its array.
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
        <div x-data="{ selected: '' }">
            <select
                name="{{ $name }}"
                {{ $required ? "required" : "" }}
                class="{{$inputStyle}} @error($name) {{$is_invalid}} @else {{$is_valid}} @enderror"
                selected="{{ old($name) }}"
                x-model="selected" x-init="$watch('selected', (value)=>{
                    //'value' corresponds to the index position of the selected method in the items data array.
                    {{ $saveSelectedIn }} = {{ $getSelectedFrom }}[value];
                });">

                <template x-if="$data.showDefaultOption">
                        <option value="" selected x-text="$data.defaultOptionText"></option>
                </template>
                <template x-for="(option, index) in $data.options">
                    <option :value="index" x-text="option.name"></option>
                </template>
                @foreach($options as $option)
                    <template x-if="$data.showBladeOptions">
                        <option value="{{$option["value"]}}" {{ $option["selected"] ? "selected" : "" }}>{{$option["title"]}}</option>
                    </template>
                @endforeach
            </select>
        </div>
    @endif
    @error($name)
        <div class="text-red text-xs my-1">{{ $message }}</div>
    @enderror
</div>

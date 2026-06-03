@props(['name', 'label', 'type'])
<div class="space-y-2">
    <label for="{{ $name }}" class="Label">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" {{ $attributes }}/>

    @error($name)
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>
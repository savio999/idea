@props(['name', 'label' => false, 'type', 'textValue' => null])
<div class="space-y-2">
    @if($label)
        <label for="{{ $name }}" class="label">{{ $label }}</label>
    @endif

    @if($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge(['class' => 'textarea']) }}>{{ $textValue }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge(['class' => 'input']) }}/>
    @endif
</div>
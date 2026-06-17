@props(['name', 'label' => false, 'type'])
<div class="space-y-2">
    @if($label)
        <label for="{{ $name }}" class="label">{{ $label }}</label>
    @endif

    @if($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}" {{ $attributes }} class="textarea"></textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" {{ $attributes }} class="input"/>
    @endif
    <x-form.error name="{{ $name }}" />
</div>
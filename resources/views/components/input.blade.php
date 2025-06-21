@props(['label', 'name', 'type' => 'text', 'value' => ''])

<div class="col-md-6">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-control"
        value="{{ $value }}"
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
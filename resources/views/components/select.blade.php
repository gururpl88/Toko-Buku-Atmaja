<div class="col-md-6">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-select" {{ $required ? 'required' : '' }}>
        <option value="">-- Pilih --</option>
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" {{ (old($name, $selected) == $key) ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>
</div>
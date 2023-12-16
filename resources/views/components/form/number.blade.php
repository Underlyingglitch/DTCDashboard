<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" type="number" name="{{ $name }}"
        placeholder="{{ $placeholder ?? $label }}" value="{{ old($name) ?? $value }}" />
</div>

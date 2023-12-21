<div class="form-group" id="{{ $name }}_field">
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" type="date"
        name="{{ $name }}" value="{{ old($name) ?? $value }}" />
</div>

<div class="form-group" id="{{ $name }}_field">
    <label for="{{ $name }}">{{ $label }}</label>
    <input wire:model="{{ $name }}" id="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror" type="text" name="{{ $name }}"
        placeholder="{{ $placeholder ?? $label }}" value="{{ old($name) ?? $value }}" />
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

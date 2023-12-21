<div class="mb-3" id="{{ $name }}_field">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="file" id="{{ $name }}" name="{{ $name }}"
        class="@error($name) is-invalid @enderror" value="{{ old('file') }}" />
</div>

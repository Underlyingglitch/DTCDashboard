<div class="mb-3">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="file" id="{{ $name }}" name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror" value="{{ old('file') }}" />
</div>

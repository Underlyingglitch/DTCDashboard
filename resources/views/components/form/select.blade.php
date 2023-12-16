<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-control @error($name) is-invalid @enderror">
        @if (is_null($value))
            <option value="--">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" @if ($key == $value) selected @endif>{{ $option }}
            </option>
        @endforeach
    </select>
</div>

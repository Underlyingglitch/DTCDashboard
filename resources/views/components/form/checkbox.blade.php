<input type="checkbox" name="{{ $name }}[]" value="{{ $value }}"
    id="{{ $name }}-checkbox-{{ $value }}" @if ($checked) checked @endif />
<label for="{{ $name }}-checkbox-{{ $value }}">{{ $label }}</label><br>

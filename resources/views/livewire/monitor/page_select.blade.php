<div>
    <select wire:model="selected_page" wire:change="setPage" id="">
        @foreach ($pages as $key => $page)
            <option value="{{ $key }}" @if ($key == $selected_page) selected @endif>{{ $page }}
            </option>
        @endforeach
    </select>
</div>

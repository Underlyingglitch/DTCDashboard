<div>
    <form wire:submit.prevent="import">
        {{-- File input --}}
        <div class="form-group">
            <label for="file">Bestand</label>
            <input type="file" class="form-control-file" id="file" wire:model="file">
            @error('file')
                {{ $message }}
            @enderror
            <div wire:loading wire:target="file">Uploaden...</div>
        </div>
        {{-- Submit button --}}
        @if ($file)
            <button type="submit" class="btn btn-primary">Importeer</button>
        @endif
    </form>
</div>

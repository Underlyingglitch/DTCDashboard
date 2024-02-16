<button class="btn btn-sm {{ $enabled ? 'btn-success' : 'btn-danger' }}"><i
        class="fas {{ $enabled ? 'fa-check' : 'fa-times' }}" wire:click="toggle()"></i></button>

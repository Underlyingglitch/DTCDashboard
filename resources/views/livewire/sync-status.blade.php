<div wire:click="sync">
    @switch($status)
        @case(1)
            <div class="alert alert-success">
                Synchroniseren gelukt.
            </div>
        @break

        @case(2)
            <div class="alert alert-danger">
                Fout met synchroniseren. Probeer het later opnieuw.
            </div>
        @break

        @default
            <div class="alert alert-info">
                Synchroniseren...
            </div>
    @endswitch
</div>

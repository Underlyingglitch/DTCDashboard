<div wire:click="sync">
    @switch($status)
        @case(0)
            <div class="alert alert-primary">
                Synchroniseren uitgeschakeld
            </div>
        @break

        @case(1)
            <div class="alert alert-warning">
                Taken in wachtrij
            </div>
        @break

        @case(2)
            <div class="alert alert-info">
                Synchroniseren...
            </div>
        @break

        @case(3)
            <div class="alert alert-success">
                Bijgewerkt
            </div>
        @break

        @default
            <div class="alert alert-danger">
                Fout met synchroniseren van {{ $message }} taken
            </div>
    @endswitch
</div>

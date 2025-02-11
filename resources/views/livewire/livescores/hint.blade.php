<div>
    @if ($shown)
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" wire:click="hide">
                <span aria-hidden="true">&times;</span>
            </button>
            Klik op een turner om de scores in detail te bekijken. De pagina wordt automatisch ververst, ook als een
            turner aangeklikt is.
        </div>
    @endif
</div>

<div>
    <input class="form-control" type="number" placeholder="Startnummer" wire:model="startnumber" wire:blur="sn_updated()" />
    <div class="row">
        <div class="col-md-6">
            <input class="form-control" type="number" placeholder="D-score" wire:model="d" wire:blur="calculate()"
                @if ($locked) disabled @endif />
            <input class="form-control" type="number" placeholder="N-score" wire:model="n" wire:blur="calculate()"
                @if ($locked) disabled @endif />
            <input class="form-control" type="number" placeholder="E-score" wire:model="e" disabled />
        </div>
        <div class="col-md-6">
            <input class="form-control" type="number" placeholder="E1-score" wire:model="e1" wire:blur="calculate()"
                @if ($locked) disabled @endif />
            <input class="form-control" type="number" placeholder="E2-score" wire:model="e2" wire:blur="calculate()"
                @if ($locked) disabled @endif />
            <input class="form-control" type="number" placeholder="E2-score" wire:model="e3" wire:blur="calculate()"
                @if ($locked) disabled @endif />
        </div>
    </div>


</div>

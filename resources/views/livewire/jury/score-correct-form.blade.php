<div>
    @if ($jury == false)
        Toestel:
        <select wire:model="toestel" class="form-control" wire:change="sn_updated()">
            <option value="1">Vloer</option>
            <option value="2">Voltige</option>
            <option value="3">Ringen</option>
            <option value="4">Sprong</option>
            <option value="5">Brug</option>
            <option value="6">Rekstok</option>
        </select>
    @endif
    Startnummer: <input class="form-control" type="number" placeholder="Startnummer" wire:model="startnumber"
        wire:keydown.debounce.1000ms="sn_updated()" tabindex="7" />
    <div class="row">
        <div class="col-md-6">
            D: <input class="form-control" type="text" step=".1" inputmode="decimal" placeholder="D-score"
                wire:model="d" wire:blur="calculate()" tabindex="8"
                @if ($locked) disabled @endif />
            N: <input class="form-control" type="text" step=".1" inputmode="decimal" placeholder="N-score"
                wire:model="n" wire:blur="calculate()" tabindex="12"
                @if ($locked) disabled @endif />
            B: <input class="form-control" type="text" step=".1" inputmode="decimal" placeholder="Bonus"
                wire:model="b" wire:blur="calculate()" tabindex="13"
                @if ($locked) disabled @endif />
            E: <input class="form-control" type="number" step=".001" placeholder="E-score" wire:model="e"
                readonly />
            Totaal: <input class="form-control" type="number" step=".001" placeholder="Totaal" wire:model="t"
                readonly />
        </div>
        <div class="col-md-6">
            E1: <input class="form-control" type="text" step=".001" inputmode="decimal" placeholder="E1-aftrek"
                wire:model="e1" wire:blur="calculate()" tabindex="9"
                @if ($locked) disabled @endif />
            E2: <input class="form-control" type="text" step=".001" inputmode="decimal" placeholder="E2-aftrek"
                wire:model="e2" wire:blur="calculate()" tabindex="10"
                @if ($locked) disabled @endif />
            E3: <input class="form-control" type="text" step=".001" inputmode="decimal" placeholder="E3-aftrek"
                wire:model="e3" wire:blur="calculate()" tabindex="11"
                @if ($locked) disabled @endif />
            &nbsp;<input class="form-control btn btn-primary" type="submit" value="Opslaan" tabindex="14"
                wire:click="save()" />
        </div>
    </div>


</div>

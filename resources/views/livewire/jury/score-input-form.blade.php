<div wire:keydown.enter="save()">
    Startnummer: <input class="form-control" type="number" placeholder="Startnummer" wire:model="startnumber" readonly />
    <div class="row">
        <div class="col-md-6">
            D: <input class="form-control" type="text" step=".1" inputmode="decimal" placeholder="D-score"
                wire:model="d" wire:blur="calculate()" @if ($locked) disabled @endif
                id="d-score-field" tabindex="1" />
            N: <input class="form-control" type="text" step=".1" inputmode="decimal" placeholder="N-score"
                wire:model="n" wire:blur="calculate()" @if ($locked) disabled @endif
                tabindex="5" />
            E: <input class="form-control" type="number" step=".001" placeholder="E-score" wire:model="e"
                readonly />
            Totaal: <input class="form-control" type="number" step=".001" placeholder="Totaal" wire:model="t"
                readonly />
        </div>
        <div class="col-md-6">
            E1: <input class="form-control" type="text" step=".001" inputmode="decimal" placeholder="E1-score"
                wire:model="e1" wire:blur="calculate()" @if ($locked) disabled @endif
                tabindex="2" />
            E2: <input class="form-control" type="text" step=".001" inputmode="decimal" placeholder="E2-score"
                wire:model="e2" wire:blur="calculate()" @if ($locked) disabled @endif
                tabindex="3" />
            E3: <input class="form-control" type="text" step=".001" inputmode="decimal" placeholder="E3-score"
                wire:model="e3" wire:blur="calculate()" @if ($locked) disabled @endif
                tabindex="4" />
            &nbsp;<input class="form-control btn btn-primary" type="submit" value="Opslaan" wire:click="save()"
                tabindex="6" />
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header text-center">
        {{ $laptop['name'] }}
    </div>
    <div class="card-body">
        @if ($laptop['device_id'])
            {{-- @livewire('monitor.user_select', ['device' => $laptop]) --}}
            <div class="text-center">
                {{-- @livewire('monitor.page_select', ['device' => $laptop, 'type' => 'jury']) --}}
                <select class="form-control" wire:model="selected_page" wire:change="setPage" id="">
                    @foreach ($pages as $key => $page)
                        <option value="{{ $key }}">
                            {{ $page }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-danger" wire:click="removeDevice">Verwijder apparaat</button>
            </div>
        @else
            <div class="text-center">
                <p class="text-muted">Geen apparaat gekoppeld</p>
                <input class="form-control" type="text" wire:model="code" wire:keydown.enter="assignDevice"
                    placeholder="Device code">
            </div>
        @endif
    </div>
</div>

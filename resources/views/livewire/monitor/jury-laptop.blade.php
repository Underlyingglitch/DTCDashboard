<div class="card">
    <div class="card-header text-center">
        <div class="d-flex justify-content-between align-items-center">
            <span>{{ $device['name'] }}</span>
            <div>
                @if ($isOnline)
                    <span class="badge badge-success">Online</span>
                @else
                    <span class="badge badge-danger">Offline</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        @if ($device['device_id'])
            {{-- Device Status Information --}}
            <div class="mb-3">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Battery</small>
                        @if ($battery)
                            <div class="progress" style="height: 20px;">
                                @php
                                    $batteryValue = $battery['value'] ?? 0;
                                    $batteryClass =
                                        $batteryValue >= 50
                                            ? 'bg-success'
                                            : ($batteryValue >= 20
                                                ? 'bg-warning'
                                                : 'bg-danger');
                                    $chargingIcon = $battery['charging'] ?? false ? '⚡' : '';
                                @endphp
                                <div class="progress-bar {{ $batteryClass }}" role="progressbar"
                                    style="width: {{ $batteryValue }}%;" aria-valuenow="{{ $batteryValue }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ $batteryValue }}% {{ $chargingIcon }}
                                </div>
                            </div>
                        @else
                            <p class="text-muted small">N/A</p>
                        @endif
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Screen</small>
                        <p class="mb-0">
                            @if ($currentState && isset($currentState['screenOn']))
                                @if ($currentState['screenOn'])
                                    <span class="badge badge-info">On</span>
                                @else
                                    <span class="badge badge-dark">Off</span>
                                @endif
                            @else
                                <span class="badge badge-secondary">Unknown</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Page Selection --}}
            <div class="text-center">
                <select class="form-control mb-2" wire:model="selected_page" wire:change="setPage">
                    @foreach ($pages as $key => $page)
                        <option value="{{ $key }}">
                            {{ $page }}
                        </option>
                    @endforeach
                </select>

                {{-- Action Buttons --}}
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-sm btn-primary" wire:click="sendAuthToken">
                        Send Auth
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" wire:click="removeDevice">
                        Remove
                    </button>
                </div>
            </div>
        @else
            <div class="text-center">
                <p class="text-muted">No device paired</p>
                <input class="form-control" type="text" wire:model="code" wire:keydown.enter="assignDevice"
                    placeholder="Device code">
            </div>
        @endif
    </div>
</div>

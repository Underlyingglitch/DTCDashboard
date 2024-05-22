<div class="card-body">
    Na ronde (0 voor alles): <input type="number" wire:model="limit" wire:change="updateLimit">
    @php($i = 0)
    @php($previous = null)
    @foreach ($registrations as $registration)
        <div class="card mb-2" wire:click="toggleModal({{ $registration['id'] }})">
            <div class="card-header">
                {{ $previous == $registration['total'] ? $i : ++$i }}. {{ $registration['name'] }}
                ({{ $registration['club'] }})
                <span class="float-right">{{ number_format($registration['total'], 3) }}</span>
            </div>
        </div>

        @if ($modalShown == $registration['id'])
            <div class="modal modal-md" style="display:block; background-color:rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $registration['name'] }} ({{ $registration['club'] }})
                            </h4>
                            <button type="button" class="btn-close" wire:click="toggleModal(0)"></button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <table style="width: 100%">
                                <tr>
                                    <th>Toestel</th>
                                    <th>D</th>
                                    <th>E</th>
                                    <th>N</th>
                                    <th>Totaal</th>
                                </tr>
                                @foreach ($registration['scores'] as $score)
                                    <tr>
                                        <td>{{ $toestellen[$score['toestel'] - 1] }}</td>
                                        <td>{{ number_format($score['d'], 3) }}</td>
                                        <td>{{ number_format($score['e'], 3) }}</td>
                                        <td>{{ number_format($score['n'], 1) }}</td>
                                        <td>{{ $score['total'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" style="text-align: center">Totaal:
                                        {{ number_format($registration['total'], 3) }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

<div class="card-body">
    @foreach ($teams as $team)
        <div class="card mb-2" wire:click="toggleModal({{ $team['id'] }})">
            <div class="card-header">
                {{ $team['place'] }}. {{ $team['name'] }}
                <span class="float-right">{{ number_format($team['total'], 3) }}</span>
            </div>
        </div>

        @if ($modalShown == $team['id'])
            <div class="modal modal-md" style="display:block; background-color:rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $team['place'] }}. {{ $team['name'] }}</h4>
                            <button type="button" class="close" wire:click="toggleModal(0)">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <table style="width: 100%">
                                <tr>
                                    <th>Toestel</th>
                                    <th>Totaal</th>
                                </tr>
                                @foreach ($team['toestel_scores'] as $i => $score)
                                    <tr>
                                        <td>{{ $toestellen[$i] }}</td>
                                        <td>{{ number_format($score, 3) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" style="text-align: center">Totaal: {{ $team['total'] }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

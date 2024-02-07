<div class="card-body">
    @php($i = 1)
    @foreach ($registrations as $registration)
        <div class="card mb-2" wire:click="toggleModal({{ $registration['id'] }})">
            <div class="card-header">
                {{ $i++ }}. {{ $registration['name'] }} ({{ $registration['club'] }})
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
                            <button type="button" class="close" wire:click="toggleModal(0)">&times;</button>
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
                                        <td>{{ $score['d'] }}</td>
                                        <td>{{ $score['e'] }}</td>
                                        <td>{{ $score['n'] }}</td>
                                        <td>{{ $score['total'] }}</td>
                                    </tr>
                                @endforeach
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

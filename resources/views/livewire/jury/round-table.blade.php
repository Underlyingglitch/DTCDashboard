<div class="card">
    <div class="card-header text-center">Ronde {{ $current_round }} | {{ implode(', ', $group_names) }}</div>
    <div class="card-body">
        <table class="table table-sm table-bordered">
            <tr>
                <th>Nr</th>
                <th>Naam</th>
                <th>Vereniging</th>
                <th>Niveau</th>
                <th>Status</th>
            </tr>
            @foreach ($group_names as $baan => $group_name)
                <tr>
                    <td colspan="4" class="text-center">{{ $group_name }}</td>
                </tr>
                @foreach ($registrations[$baan] as $registration)
                    <tr
                        @if ($registration['status'] == 'signed_off') style="text-decoration: line-through" @else wire:click="clicked({{ $registration['startnumber'] }})" @endif>
                        <td>{{ $registration['startnumber'] }}</td>
                        <td>{{ $registration['name'] }}</td>
                        <td>{{ $registration['club'] }}</td>
                        <td>{{ $registration['niveau'] }}</td>
                        <td>{!! $jury_registration_status[$registration['status']] !!}
                            @if ($registration['status'] == 'scored')
                                <i>{{ $registration['score'] }}</i>
                            @elseif ($registration['status'] == 'correction_pending')
                                <del>{{ $registration['score'] }}</del>
                                <i>{{ $registration['new_score'] }}</i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>
</div>

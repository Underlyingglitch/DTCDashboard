<div>
    @if ($error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif
    @if (is_null($doorstroom))
        <label for="niveau">Niveau</label>
        <select wire:model="niveau" wire:change="updateNiveau" id="niveau" class="form-control">
            @foreach ($niveaus as $niveau)
                <option value="{{ $niveau->id }}">{{ $niveau->full_name }}</option>
            @endforeach
        </select>
        <label for="">Type: (geen, plaatsing, finale)</label><br>
        @foreach ($match_days ?? [] as $index => $match_day)
            <label for="{{ $match_day->id }}">{{ $match_day->name }}</label>
            <input id="{{ $match_day->id }}" type="radio" wire:model="match_days_selection.{{ $match_day->id }}"
                value="0">
            <input id="{{ $match_day->id }}" type="radio" wire:model="match_days_selection.{{ $match_day->id }}"
                value="1">
            <input id="{{ $match_day->id }}" type="radio" wire:model="match_days_selection.{{ $match_day->id }}"
                value="2"><br>
        @endforeach
        <label for="amount">Aantal</label>
        <input wire:model="amount" type="number" id="amount" class="form-control"><br>
        <button class="btn btn-sm btn-primary" wire:click="process">Doorstroming berekenen</button>
    @else
        <button wire:click="back" class="btn btn-sm btn-primary">Terug</button>
        <button wire:click="togglePointsShown"
            class="btn btn-sm btn-info">{{ $points_shown ? 'Punten niet tonen' : 'Punten tonen' }}</button>
        <table border="1">
            @if ($teams)
                @foreach ($doorstroom as $team)
                    <tr>
                        <th colspan="4">{{ $team['name'] }}</th>
                        @if ($points_shown)
                            @for ($i = 0; $i < count($match_days_selection); $i++)
                                <th>{{ $team['scores'][$i] ?? '-' }}</th>
                            @endfor
                            <th>{{ $team['total'] }}</th>
                        @endif
                    </tr>
                    @foreach ($team['registrations'] as $registration)
                        <tr>
                            <td>{{ $registration['name'] }}</td>
                            <td>{{ $registration['club'] }}</td>
                            <td>{{ $registration['gymnast_id'] }}</td>
                            <td>{{ $registration['club_id'] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                {{-- @php(dd($match_days_selection)) --}}
                @foreach ($doorstroom as $registration)
                    <tr>
                        <td>{{ $registration['name'] }}</td>
                        <td>{{ $registration['club'] }}</td>
                        <td>{{ $registration['gymnast_id'] }}</td>
                        <td>{{ $registration['club_id'] }}</td>
                        @if ($points_shown)
                            @for ($i = 0; $i < count($match_days_selection); $i++)
                                <th>{{ $registration['scores'][$i] ?? '' }}</th>
                            @endfor
                            <th>{{ $registration['total'] }}</th>
                        @endif
                    </tr>
                @endforeach
            @endif
        </table>
    @endif

</div>

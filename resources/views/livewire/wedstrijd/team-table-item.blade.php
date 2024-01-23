<tr @if ($registration->signed_off) style="text-decoration: line-through" @endif>
    <td>{{ $registration->startnumber }}</td>
    <td>{{ $registration->gymnast->name }}</td>
    <td>{{ $registration->club->name }}</td>
    <td>{{ $registration->niveau->name }} {{ $registration->niveau->supplement }}</td>
    @can('manage', \App\Models\Registration::class)
        <td>
            <select wire:model.live="team">
                <option value="--">--</option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}">
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
            {{-- <a href="{{ route('teams.registration.add', [$wedstrijd, $registration]) }}"
                class="btn btn-sm btn-info">Verplaatsen</a>
            <a href="{{ route('teams.registration.remove', [$wedstrijd, $registration]) }}"
                class="btn btn-sm btn-danger">Verwijderen</a> --}}

        </td>
    @endcan
</tr>

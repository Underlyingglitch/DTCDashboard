@extends('layouts.app')

@section('page_title', $wedstrijd->match_day->location->name . ' | ' . $wedstrijd->match_day->date . ' | Wedstrijd ' .
    $wedstrijd->index)

@section('content')
    <div class="d-flex flex-row justify-content-between">
        <div>
            <a href="{{ route('matchdays.show', $wedstrijd->match_day_id) }}" class="btn btn-sm btn-primary">Terug naar
                wedstrijddag</a>
            @can('update', $wedstrijd)
                <a href="{{ route('wedstrijden.edit', $wedstrijd) }}" class="btn btn-sm btn-warning">Bewerken</a>
            @endcan
            @can('process_scores', $wedstrijd)
                <a href="{{ route('wedstrijden.score.index', $wedstrijd) }}" class="btn btn-sm btn-info">Scoreverwerking</a>
            @endcan
        </div>
        <div class="col-md-4">
            @can('export', $wedstrijd)
                <form action="{{ route('wedstrijden.export.select', $wedstrijd) }}" method="post" target="_blank">
                    @csrf
                    <div class="input-group">
                        <select name="option" id="option"
                            class="form-control form-control-sm @error('option') is-invalid @enderror">
                            <option value="--">--</option>
                            @foreach ($export_options as $key => $option)
                                <option value="{{ $key }}">
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        <input type="submit" value="Exporteren" class="btn btn-sm btn-info">
                    </div>
                </form>
            @endcan
        </div>
    </div>
    <br>

    <h4>Groepen</h4>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Naam</th>
                <th>Club</th>
                <th>Niveau</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Naam</th>
                <th>Club</th>
                <th>Niveau</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($groups as $registrations)
                <tr>
                    <th></th>
                    <th colspan="4">
                        @if ($wedstrijd_baans > 1)
                            Baan {{ $registrations->first()->group->baan }} -
                        @endif
                        {{ $registrations->first()->group->name }}
                    </th>
                </tr>
                {{-- @foreach ($wedstrijd->registrations()->where('group_id', $group->id)->get() ?? [] as $registration) --}}
                @foreach ($registrations ?? [] as $registration)
                    <tr @if ($registration->signed_off) style="text-decoration: line-through" @endif>
                        <td>{{ $registration->startnumber }}</td>
                        <td>{{ $registration->gymnast->name }}</td>
                        <td>{{ $registration->club->name }}</td>
                        <td>{{ $registration->niveau->name }} {{ $registration->niveau->supplement }}</td>
                        <td>
                            @can('manage', $registration)
                                <a href="{{ route('wedstrijden.registration.move_group', [$wedstrijd, $registration]) }}"
                                    class="btn btn-sm btn-primary">Verplaatsen</a>
                            @endcan
                            @can('signoff', $registration)
                                @if ($registration->signed_off)
                                    <a href="{{ route('wedstrijden.registration.signoff', [$wedstrijd, $registration]) }}"
                                        class="btn btn-sm btn-success">Aanmelden</a>
                                @else
                                    <a href="{{ route('wedstrijden.registration.signoff', [$wedstrijd, $registration]) }}"
                                        class="btn btn-sm btn-warning">Afmelden</a>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <hr>
    <h4>Teams</h4>
    @can('create', \App\Models\Team::class)
        <a href="{{ route('teams.create', $wedstrijd) }}" class="btn btn-sm btn-success">Team aanmaken</a>
    @endcan

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Naam</th>
                <th>Club</th>
                <th>Niveau</th>
                <th>Groep</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Naam</th>
                <th>Club</th>
                <th>Niveau</th>
                <th>Groep</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($niveaus ?? [] as $team_list)
                @foreach ($team_list ?? [] as $team_members)
                    @php($team = $team_members->first()->team)
                    <tr>
                        <th></th>
                        <th colspan="5">
                            {{ $team->name }}
                            @can('update', $team)
                                <a href="{{ route('teams.edit', [$wedstrijd, $team]) }}"
                                    class="btn btn-sm btn-primary">Bewerken</a>
                            @endcan
                            @can('delete', $team)
                                <form class="button-form" method="post"
                                    action="{{ route('teams.destroy', [$wedstrijd, $team]) }}">
                                    @csrf
                                    @method('delete')
                                    <input class="btn btn-sm btn-danger" type="submit" value="Verwijderen" />
                                </form>
                            @endcan
                        </th>
                    </tr>
                    @foreach ($team_members ?? [] as $registration)
                        <tr @if ($registration->signed_off) style="text-decoration: line-through" @endif>
                            <td>{{ $registration->startnumber }}</td>
                            <td>{{ $registration->gymnast->name }}</td>
                            <td>{{ $registration->club->name }}</td>
                            <td>{{ $registration->niveau->name }} {{ $registration->niveau->supplement }}</td>
                            <td>{{ $wedstrijd_baans > 1 ? $registration->group->full_name : $registration->group->name }}
                            </td>
                            <td>
                                @can('manager', $registration)
                                    <a href="{{ route('teams.registration.add', [$wedstrijd, $registration]) }}"
                                        class="btn btn-sm btn-info">Verplaatsen</a>
                                    <a href="{{ route('teams.registration.remove', [$wedstrijd, $registration]) }}"
                                        class="btn btn-sm btn-danger">Verwijderen</a>
                                @endcan
                                @can('signoff', $registration)
                                    @if ($registration->signed_off)
                                        <a href="{{ route('wedstrijden.registration.signoff', [$wedstrijd, $registration]) }}"
                                            class="btn btn-sm btn-success">Aanmelden</a>
                                    @else
                                        <a href="{{ route('wedstrijden.registration.signoff', [$wedstrijd, $registration]) }}"
                                            class="btn btn-sm btn-warning">Afmelden</a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
            <tr>
                <th></th>
                <th colspan="5">Zonder team</th>
            </tr>
            @foreach ($wedstrijd_no_team ?? [] as $registration)
                <tr @if ($registration->signed_off) style="text-decoration: line-through" @endif>
                    <td>{{ $registration->startnumber }}</td>
                    <td>{{ $registration->gymnast->name }}</td>
                    <td>{{ $registration->club->name }}</td>
                    <td>{{ $registration->niveau->name }} {{ $registration->niveau->supplement }}</td>
                    <td>
                        @can('manage', $registration)
                            <a href="{{ route('teams.registration.add', [$wedstrijd, $registration]) }}"
                                class="btn btn-sm btn-primary">Toevoegen (team)</a>
                        @endcan
                        @can('signoff', $registration)
                            @if ($registration->signed_off)
                                <a href="{{ route('wedstrijden.registration.signoff', [$wedstrijd, $registration]) }}"
                                    class="btn btn-sm btn-success">Aanmelden</a>
                            @else
                                <a href="{{ route('wedstrijden.registration.signoff', [$wedstrijd, $registration]) }}"
                                    class="btn btn-sm btn-warning">Afmelden</a>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

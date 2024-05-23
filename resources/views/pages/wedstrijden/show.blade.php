@extends('layouts.app')

@section('page_title',
    $wedstrijd->match_day->location->name .
    ' | ' .
    $wedstrijd->match_day->date->format('d-m-Y') .
    '
    | Wedstrijd ' .
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
    <div style="overflow-x: auto;">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Naam</th>
                    <th>Club</th>
                    <th>Niveau</th>
                    <th>Aangemeld</th>
                    @can('manage', \App\Models\Registration::class)
                        <th>Groep</th>
                    @endcan
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Naam</th>
                    <th>Club</th>
                    <th>Niveau</th>
                    <th>Aangemeld</th>
                    @can('manage', \App\Models\Registration::class)
                        <th>Groep</th>
                    @endcan
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
                    @foreach ($registrations ?? [] as $registration)
                        @livewire('wedstrijd.group-table-item', ['registration' => $registration], key($registration->id))
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <hr>
    <h4>Teams</h4>
    @can('create', \App\Models\Team::class)
        <a href="{{ route('teams.create', $wedstrijd) }}" class="btn btn-sm btn-success">Team aanmaken</a>
    @endcan

    <div style="overflow-x: auto;">
        <table class="table table-sm">
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
                @foreach ($niveaus ?? [] as $team_list)
                    @foreach ($team_list ?? [] as $team_members)
                        @php($team = $team_members->first()->team)
                        @if ($team == null)
                            @php(dd($team_members))
                        @endif
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
                            @livewire('wedstrijd.team-table-item', ['registration' => $registration, 'wedstrijd' => $wedstrijd, 'wedstrijd_baans' => $wedstrijd_baans])
                        @endforeach
                    @endforeach
                @endforeach
                <tr>
                    <th></th>
                    <th colspan="5">Zonder team</th>
                </tr>
                @foreach ($no_team ?? [] as $registration)
                    @livewire('wedstrijd.team-table-item', ['registration' => $registration, 'wedstrijd' => $wedstrijd, 'wedstrijd_baans' => $wedstrijd_baans])
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

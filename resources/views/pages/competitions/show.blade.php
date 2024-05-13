@extends('layouts.app')

@section('page_title', $competition->name)

@section('content')
    <a href="{{ route('competitions.index') }}" class="btn btn-sm btn-primary">Terug naar competities</a>
    <h4>Wedstrijddagen</h4>
    @can('create', \App\Models\MatchDay::class)
        <a href="{{ route('matchdays.create', $competition) }}" class="btn btn-sm btn-success">Nieuwe wedstrijddag</a>
    @endcan
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Datum</th>
                    <th>Locatie</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Naam</th>
                    <th>Datum</th>
                    <th>Locatie</th>
                    <th>Acties</th>
                </tr>
            </tfoot>
            <tbody>
                @foreach ($matchdays as $matchday)
                    <tr>
                        <td>{{ $matchday->name }}</td>
                        <td>{{ $matchday->date->format('d-m-Y') }}</td>
                        <td>{{ $matchday->location->name }}</td>
                        <td>
                            @can('view', $matchday)
                                <a href="{{ route('matchdays.show', $matchday) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-info-circle"></i></a>
                            @endcan
                            @can('update', $matchday)
                                <a href="{{ route('matchdays.edit', $matchday) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-pencil"></i></a>
                            @endcan
                            @can('delete', $matchday)
                                <form class="button-form" method="post" action="{{ route('matchdays.destroy', $matchday) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @can('processDoorstroom', $competition)
        <h4>Doorstroming berekenen</h4>
        @livewire('calculate-doorstroom', ['competition' => $competition])
    @endcan
@endsection

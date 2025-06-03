@extends('pdf.template')

@section('title', $wedstrijd->match_day->location->name . ' ' . $wedstrijd->match_day->date->format('d-m-Y') . ' W' .
    $wedstrijd->index . ' teams')

@section('header')
    @if ($wedstrijd->match_day->competition->kngu_competition)
        <img class="header-img"
            src="{{ config('app.debug') ? asset('img/kngu_header.png') : public_path('img/kngu_header.png') }}"
            alt="">
    @endif
    <h2 class="title">{{ $wedstrijd->competition->name }}</h2>
    <h2 class="subtitle">Locatie: {{ $wedstrijd->match_day->location->name }}</h2>
    <p><a class="no-print" href="{{ route('wedstrijden.export.groups', $wedstrijd->id - 1) }}">
            &lArr;</a> Wedstrijd {{ $wedstrijd->index }} | {{ $wedstrijd->niveaus_list }} <a class="no-print"
            href="{{ route('wedstrijden.export.groups', $wedstrijd->id + 1) }}">&rArr;</a>
    </p>
@endsection

@section('main')
    @foreach ($niveaus as $teams)
        <b>{{ $teams->first()->niveau->full_name }}</b>
        @foreach ($teams as $team)
            <table class="group-table">
                <tr>
                    <th colspan="2">{{ $team->name }}</th>
                    <th>Team</th>
                    <th>Niveau</th>
                </tr>
                @foreach ($team->registrations as $registration)
                    <tr
                        @if ($registration->signed_off) style="text-decoration:line-through;text-decoration-thickness:2px" @endif>
                        <td style="width: 20%">{{ $registration->gymnast->name }}</td>
                        <td style="width: 35%">{{ $registration->club->name }}</td>
                        <td style="width: 35%">{{ $team->name ?? '-' }}</td>
                        <td style="width: 10%">{{ $team->niveau->full_name }}</td>
                    </tr>
                @endforeach
            </table>
        @endforeach
    @endforeach
@endsection

@extends('pdf.template')

@section('title', 'Teamindeling W' . $wedstrijd->index . ' - ' . $wedstrijd->match_day->location->name)

@section('header')
    <img class="header-img" src="{{ asset('img/kngu_header.png') }}" alt="">
    <h2 class="title">{{ $wedstrijd->competition->name }}</h2>
    <h2 class="subtitle">Locatie: {{ $wedstrijd->match_day->location->name }}</h2>
    <p><a class="no-print" href="{{ route('wedstrijden.export.groups', $wedstrijd->id - 1) }}">
            &lArr;</a> Wedstrijd {{ $wedstrijd->index }} | {{ $wedstrijd->niveaus_list }} <a class="no-print"
            href="{{ route('wedstrijden.export.groups', $wedstrijd->id + 1) }}">&rArr;</a>
    </p>
@endsection

@section('main')
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
                    <td style="width: 10%">{{ $registration->niveau->name }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach
@endsection

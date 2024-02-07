@extends('pdf.template')

@section('title', $wedstrijd->match_day->location->name . ' ' . $wedstrijd->match_day->date->format('d-m-Y') . ' W' .
    $wedstrijd->index)

@section('header')
    <img class="header-img"
        src="{{ config('app.debug') ? asset('img/kngu_header.png') : public_path('img/kngu_header.png') }}" alt="">
    <h2 class="title">{{ $wedstrijd->competition->name }}</h2>
    <h2 class="subtitle">Locatie: {{ $wedstrijd->match_day->location->name }}</h2>
    <p><a class="no-print" href="{{ route('wedstrijden.export.groups', $wedstrijd->id - 1) }}">
            &lArr;</a> Wedstrijd {{ $wedstrijd->index }} | {{ $wedstrijd->niveaus_list }} <a class="no-print"
            href="{{ route('wedstrijden.export.groups', $wedstrijd->id + 1) }}">&rArr;</a>
    </p>
@endsection

@section('main')
    @foreach ($groups as $group)
        <table class="group-table">
            <tr style="page-break-after: avoid">
                <th colspan="3" class="group-name">
                    {{ $wedstrijd->baans($groups) > 1 ? $group->full_name : $group->name }}
                </th>
                <th style="width: 80px">Team #</th>
                <th style="width: 80px">Niveau</th>
            </tr>
            @foreach ($registrations->where('group_id', $group->id) as $registration)
                <tr
                    @if ($registration->signed_off) style="page-break-after:avoid;text-decoration:line-through;text-decoration-thickness:2px" @else style="page-break-after:avoid;" @endif>
                    <td style="width: 20px">{{ $registration->startnumber }}</td>
                    <td>{{ $registration->gymnast->name }}</td>
                    <td style="width: 30%">{{ $registration->club->name }}</td>
                    <td>
                        @if ($registration->team)
                            {{ array_search($registration->team->id, array_column($teams, 'id')) + 1 }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $registration->niveau->full_name }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

    Teamnamen:<br>
    @foreach ($teams as $key => $team)
        {{ $key + 1 }} - {{ $team->name }}<br>
    @endforeach
@endsection

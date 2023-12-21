@extends('pdf.template')

@section('title', 'Groepsindeling W' . $wedstrijd->index . ' - ' . $wedstrijd->match_day->location->name)

@section('header')
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
            <tr>
                <th colspan="3" class="group-name">
                    {{ $wedstrijd->baans($groups) > 1 ? $group->full_name : $group->name }}
                </th>
                <th style="width: 80px">Team #</th>
                <th style="width: 80px">Niveau</th>
            </tr>
            @foreach ($registrations->where('group_id', $group->id) as $registration)
                <tr
                    @if ($registration->signed_off) style="text-decoration:line-through;text-decoration-thickness:2px" @endif>
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
                    <td>{{ $registration->niveau->name }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

    Teamnamen:<br>
    @foreach ($teams as $key => $team)
        {{ $key + 1 }} - {{ $team->name }}<br>
    @endforeach
@endsection

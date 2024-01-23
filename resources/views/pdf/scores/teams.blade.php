@extends('pdf.template')

@section('title', 'Teamindeling W' . $wedstrijd->index . ' - ' . $wedstrijd->match_day->location->name)

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
    @foreach ($niveaus as $teams)
        <b>{{ $teams->first()->niveau->full_name }}</b>
        <table class="group-table">
            @php($i = 0)
            @php($previous = null)
            @foreach ($teams as $team)
                @php($team_total = $team->team_scores->first()->total_score ?? 0)
                <tr style="break-after: avoid">
                    <th colspan="2">{{ $previous == $team_total ? $i : ++$i }}.
                        {{ $team->name }}</th>
                    @php($previous = $team_total)
                    @foreach ($toestellen as $toestel)
                        <th colspan="2">{{ $toestel }}</th>
                    @endforeach
                </tr>
                @foreach ($team->registrations as $registration)
                    <tr style="break-after: avoid">
                        <td style="width: 10">{{ $registration->startnumber }}</td>
                        <td>
                            {{ $registration->gymnast->name }}<br>{{ $registration->club->name }}</td>
                        @foreach ($toestellen as $key => $toestel)
                            @php($score = $registration->scores->where('toestel', $key + 1)->first())
                            <td style="width: fit-content; border-right: none; font-size: 8px">
                                d:
                                {{ number_format($score->d ?? 0, 3) }}<br>
                                e:
                                {{ number_format($score->e ?? 0, 3) }}<br>
                                @if ($score->n ?? 0 != 0)
                                    n:
                                    -{{ number_format($score->n ?? 0, 1) }}
                                @endif
                            </td>
                            <td @if (($score->counted ?? 0) == 0) class="not-counted" @endif
                                style="width: fit-content; border-left:none">
                                {{ number_format($score->total ?? 0, 3) }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr style="break-after: auto">
                    <td style="width: min-content"></td>
                    <td>Totaal: {{ $team_total }}</td>
                    @foreach ($toestellen as $key => $toestel)
                        <td colspan="2" style="width: fit-content">
                            {{ number_format($team->team_scores->first()->toestel_scores[$key] ?? 0, 3) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
        <div style="page-break-after: always"></div>
    @endforeach
@endsection

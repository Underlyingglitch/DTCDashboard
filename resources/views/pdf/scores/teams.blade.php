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
                <tr style="break-after: avoid">
                    <th colspan="2">{{ $previous == $team->team_scores->first()->total_score ?? 0 ? $i : ++$i }}.
                        {{ $team->name }}</th>
                    @php($previous = $team->team_scores->first()->total_score ?? 0)
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
                            <td style="width: fit-content; border-right: none; font-size: 8px">
                                d:
                                {{ number_format($registration->scores->where('toestel', $key + 1)->first()->d ?? 0, 3) }}<br>
                                e:
                                {{ number_format($registration->scores->where('toestel', $key + 1)->first()->e ?? 0, 3) }}<br>
                                @if ($registration->scores->where('toestel', $key + 1)->first()->n ?? 0 != 0)
                                    n:
                                    -{{ number_format($registration->scores->where('toestel', $key + 1)->first()->n ?? 0, 1) }}
                                @endif
                            </td>
                            <td style="width: fit-content; border-left:none">
                                {{ number_format($registration->scores->where('toestel', $key + 1)->first()->total ?? 0, 3) }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr style="break-after: auto">
                    <td style="width: min-content"></td>
                    <td>Totaal: {{ $team->team_scores->first()->total_score ?? 0 }}</td>
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

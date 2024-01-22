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
    <table class="group-table" style="page-break-inside: auto">
        @foreach ($niveaus as $registrations)
            <tr>
                <th colspan="2">{{ $registrations->first()->niveau->full_name }}</th>
                @foreach ($toestellen as $toestel)
                    <th colspan="2">{{ $toestel }}</th>
                @endforeach
                <th>Totaal</th>
                <th>Plaats</th>
            </tr>
            @php($i = 1)
            @foreach ($registrations as $registration)
                <tr
                    @if ($registration->signed_off) style="text-decoration:line-through;text-decoration-thickness:2px" @endif>
                    <td style="width: 5%">{{ $registration->startnumber }}</td>
                    <td>{{ $registration->gymnast->name }}<br>{{ $registration->club->name }}</td>
                    @foreach ($toestellen as $key => $toestel)
                        <td style="width: 5%; border-right: none; font-size: 8px">
                            d:
                            {{ number_format($registration->scores->where('toestel', $key + 1)->first()->d ?? 0, 3) }}<br>
                            e:
                            {{ number_format($registration->scores->where('toestel', $key + 1)->first()->e ?? 0, 3) }}<br>
                            @if ($registration->scores->where('toestel', $key + 1)->first()->n ?? 0 != 0)
                                n:
                                -{{ number_format($registration->scores->where('toestel', $key + 1)->first()->n ?? 0, 1) }}
                            @endif
                        </td>
                        <td style="width: 5%; border-left:none">
                            {{ number_format($registration->scores->where('toestel', $key + 1)->first()->total ?? 0, 3) }}
                        </td>
                    @endforeach
                    <td>{{ number_format($registration->scores->sum('total') ?? 0, 3) }}</td>
                    <td>{{ $i++ }}</td>
                </tr>
            @endforeach
            <tr style="page-break-after: always; border:none">
            </tr>
        @endforeach
    </table>
@endsection

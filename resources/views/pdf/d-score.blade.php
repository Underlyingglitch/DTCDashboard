@extends('pdf.template')

@section('title', 'D-score formulieren - W' . $wedstrijd->index)

@section('main')
    @foreach ($baans as $baan => $group_nrs)
        @foreach ($toestellen as $key => $toestel)
            @foreach (array_reverse($group_nrs) as $i => $group_nr)
                @if ($group_nr[$key])
                    <table class="group-table" style="page-break-inside: avoid;">
                        <tr>
                            <th colspan="3" class="group-name">{{ $wedstrijd->match_day->date->format('d-m-Y') }} |
                                Wedstrijd
                                {{ $wedstrijd->index }} | @if (count($baans) > 1)
                                    Baan {{ $baan + 1 }} |
                                @endif Groep {{ $group_nr[$key] - $baan * 10 }} |
                                {{ $toestel }} | Ronde {{ count($group_nrs) - $i }}</th>
                            <th style="width: 80px">Niveau</th>
                            <th style="width: 50px">D-score</th>
                        </tr>
                        @foreach ($registrations->where('group_id', $group_nr[$key]) as $registration)
                            <tr>
                                <td style="width: 20px">{{ $registration->startnumber }}</td>
                                <td>{{ $registration->gymnast->name }}</td>
                                <td style="width: 30%">{{ $registration->club->name }}</td>
                                <td>{{ $registration->niveau->full_name }} @if ($registration->niveau->niveau_number)
                                        ({{ $registration->niveau->niveau_number }})
                                    @endif
                                </td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endforeach
        @endforeach
    @endforeach
@endsection

@extends('pdf.template')

@section('title', 'Jurybriefjes - W' . $wedstrijd->index)

@section('main')
    @foreach ($baans as $baan => $group_nrs)
        @foreach ($toestellen as $key => $toestel)
            @foreach ($digital ? $group_nrs : array_reverse($group_nrs) as $i => $group_nr)
                @if ($group_nr[$key])
                    <table class="group-table" style="page-break-inside: avoid;">
                        <tr>
                            <th colspan="2" class="group-name">{{ $wedstrijd->match_day->date->format('d-m-Y') }} |
                                W
                                {{ $wedstrijd->index }} | @if (count($baans) > 1)
                                    B {{ $baan + 1 }} |
                                @endif G {{ $group_nr[$key] - $baan * 10 }} |
                                {{ $toestel }} | R {{ $digital ? $i + 1 : count($group_nrs) - $i }}</th>
                            <th style="width: 80px">Niveau</th>
                            <th style="width: 50px">D-score</th>
                            <th style="width: 50px">E1-aftrek</th>
                            <th style="width: 50px">E2-aftrek</th>
                            <th style="width: 50px">E3-aftrek</th>
                            <th style="width: 50px">N-aftrek</th>
                            <th style="width: 50px">Bonus</th>
                            <th style="width: 50px">Totaal</th>
                        </tr>
                        @foreach ($registrations->where('group_id', $group_nr[$key]) as $registration)
                            <tr
                                @if ($registration->signed_off) style="text-decoration:line-through;text-decoration-thickness:2px" @endif>
                                <td style="width: 20px">{{ $registration->startnumber }}</td>
                                <td>{{ $registration->gymnast->name }} <br> {{ $registration->club->name }}</td>
                                <td>{{ $registration->niveau->full_name }} @if ($registration->niveau->niveau_number)
                                        ({{ $registration->niveau->niveau_number }})
                                    @endif
                                </td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                                <td>{{ $registration->signed_off ? '-' : '' }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endforeach
            @if ($digital)
                <div style="height:0; page-break-after:always"></div>
            @endif
        @endforeach
    @endforeach
@endsection

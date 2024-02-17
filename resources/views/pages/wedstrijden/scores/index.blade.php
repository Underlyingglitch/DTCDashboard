@extends('layouts.app')

@section('page_title', 'Scoreverwerking')

@section('content')
    <a href="{{ route('wedstrijden.show', $wedstrijd) }}" class="btn btn-sm btn-primary">Terug naar wedstrijd</a>
    @livewire('scores.recalculate-scores-button', ['wedstrijd' => $wedstrijd])
    @livewire('scores.refresh-processed-scores-button', ['wedstrijd' => $wedstrijd])
    <div style="float:right">
        @livewire('scores.set-round-button')
    </div>
    <h4>Wedstrijd {{ $wedstrijd->index }} | {{ $wedstrijd->match_day->date->format('d-m-Y') }} |
        {{ $wedstrijd->match_day->location->name }}
        | {{ $wedstrijd->niveaus_list }}</h4>

    {{-- @php(dd($baans)) --}}
    <table class="table">
        <tr>
            <th>Ronde</th>
            @for ($i = 0; $i < count($groups[0]); $i++)
                <th>{{ $toestellen[$i] ?? 'Rust' }}</th>
            @endfor
            {{-- @for ($i = 0; $i < $rounds; $i++)
                <th>{{ $i < 6 ? $toestellen[$i] : 'Rust' }}</th>
            @endfor --}}
        </tr>
        @for ($i = 0; $i < count($groups); $i++)
            <tr>
                <th>{{ $i + 1 }}</th>
                @for ($j = 0; $j < count($groups[0]); $j++)
                    <td>
                        @foreach ($groups[$i][$j] as $baan => $group)
                            @livewire('scores.score-table-button', ['wedstrijd' => $wedstrijd->id, 'groupnr' => $group, 'pss' => $pss, 'toestel' => $j + 1])
                        @endforeach
                    </td>
                @endfor
            </tr>
        @endfor
        {{-- @for ($i = 0; $i < $rounds; $i++)
            <tr>
                <th>{{ $i + 1 }}</th>
                @for ($j = 0; $j < $rounds; $j++)
                    <td>
                        @foreach ($baans as $baan => $groups)
        @livewire('scores.score-table-button', ['wedstrijd' => $wedstrijd->id, 'groupnr' => $groups[$i][$j] ?? null, 'pc' => $pc, 'toestel' => $j + 1])
        @endforeach
        </td>
        @endfor
        </tr>
        @endfor --}}
    </table>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">Score correctie toevoegen</div>
                <div class="card-body">
                    @livewire('jury.score-correct-form', ['toestel' => null, 'matchday' => $wedstrijd->match_day])
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Score correcties beheren</div>
                <div class="card-body">
                    Juryleden kunnen correcties invoeren: @livewire('jury.score-correction-enabled-button')
                    @livewire('jury.score-corrections', ['matchday' => $wedstrijd->match_day])
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('page_title', 'Scoreverwerking')

@section('content')
    <a href="{{ route('wedstrijden.show', $wedstrijd) }}" class="btn btn-sm btn-primary">Terug naar wedstrijd</a>
    <a href="{{ route('wedstrijden.score.recalculate', $wedstrijd) }}" class="btn btn-sm btn-warning">Scores
        herberekenen</a>
    <h4>Wedstrijd {{ $wedstrijd->index }} | {{ $wedstrijd->match_day->date }} | {{ $wedstrijd->match_day->location->name }}
        | {{ $wedstrijd->niveaus_list }}</h4>

    {{-- @php(dd($baans)) --}}
    <table class="table">
        <tr>
            <th>Ronde</th>
            @for ($i = 0; $i < $rounds; $i++)
                <th>{{ $i < 6 ? $toestellen[$i] : 'Rust' }}</th>
            @endfor
        </tr>
        @for ($i = 0; $i < $rounds; $i++)
            <tr>
                <th>{{ $i + 1 }}</th>
                @for ($j = 0; $j < $rounds; $j++)
                    <td>
                        @foreach ($baans as $baan => $groups)
                            {{-- Group number = $baan[$i][$j] --}}
                            <x-elements.score-table-button :wedstrijd="$wedstrijd->id" :groupnr="$groups[$i][$j] ?? null" :pc="$pc"
                                :toestel="$j + 1" />
                        @endforeach
                    </td>
                @endfor
            </tr>
        @endfor
    </table>

    <h5>Scorecorrectie</h5>
    <div class="col-md-3">
        <form action="{{ route('wedstrijden.score.correct', $wedstrijd) }}" method="post">
            @csrf
            <input class="form-control" name="startnumber" type="number" placeholder="Startnummer" />
            <label for="">Toestel</label>
            <input class="form-control" name="toestel" type="number" placeholder="Toestel" />
            <label for="">D-score</label>
            <input class="form-control" data-type="score" name="d" type="number" value="0" step=".01" />
            <label for="">E-after</label>
            <input class="form-control" data-type="score" name="e" type="number" value="0" step=".001" />
            <label for="">N-score</label>
            <input class="form-control" data-type="score" name="n" type="number" value="0" step=".01" />
            <label for=""></label>
            <div class="input-group mb-3">
                <input class="form-control" data-type="score" name="t" type="number" placeholder="Totaal"
                    step=".001" readonly />
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Opslaan</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(`.form-control[data-type='score']`).on('focus', function() {
                if ($(this).val() == 0) {
                    $(this).val('')
                }
            })
            $(`.form-control[data-type='score']`).on('blur', function() {
                if ($(this).val() == '') {
                    $(this).val(0)
                    put_total()
                }
            })
            $(`.form-control[data-type='score']`).on('change', function() {
                put_total()
            })

            function put_total() {
                var d = $(`.form-control[name='d']`).val()
                var e = $(`.form-control[name='e']`).val()
                var n = $(`.form-control[name='n']`).val()
                if (d == 0 && e == 0 && n == 0) {
                    var total = ''
                } else {
                    var total = Math.round(((parseFloat(d) + (10 - parseFloat(e))) - parseFloat(n)) * 1000) / 1000
                }
                $(`.form-control[name='t']`).val(total)
            }
        })
    </script>
@endsection

@extends('layouts.app')

@section('page_title', 'Scoreverwerking')

@section('content')
    <h4>Wedstrijd {{ $wedstrijd->index }} | {{ $wedstrijd->match_day->date }} | {{ $toestellen[$toestel - 1] }} | Baan
        {{ $group->baan }} Groep
        {{ $group->nr }}</h4>

    <table class="table">
        <tr>
            <th>Nr</th>
            <th>Naam</th>
            <th>D-score</th>
            <th>E-aftrek</th>
            <th>N-score</th>
            <th>Totaal</th>
            <th>Overslaan</th>
        </tr>
        <form id="scoreform" method="post" action="{{ route('wedstrijden.score.store', [$wedstrijd, $toestel, $group]) }}">
            @csrf
            <input type="hidden" name="ids">
            {{-- @php(dd($competition)) --}}
            @foreach ($registrations as $registration)
                <tr>
                    <td style="width: 20px">{{ $registration->startnumber }}</td>
                    <td @if ($registration->signed_off) style="text-decoration: line-through" @endif>
                        {{ $registration->gymnast->name }}</td>
                    @if ($registration->signed_off)
                        <td colspan="5"></td>
                    @elseif($registration->scores->where('toestel', $toestel)->count() > 0)
                        @php($score = $registration->scores->where('toestel', $toestel)->first())
                        <td>
                            {{ $score->d }}
                        </td>
                        <td>
                            {{ $score->e }}
                        </td>
                        <td>
                            {{ $score->n }}
                        </td>
                        <td>
                            {{ $score->total }}
                        </td>
                        <td></td>
                    @else
                        <td>
                            <input style="width: 200px" class="form-control" type="number"
                                name="d-{{ $registration->startnumber }}" value="0" step=".01">
                        </td>
                        <td>
                            <input style="width: 200px" class="form-control" type="number"
                                name="e-{{ $registration->startnumber }}" value="0" step=".001">
                        </td>
                        <td>
                            <input style="width: 200px" class="form-control" type="number"
                                name="n-{{ $registration->startnumber }}" value="0" step=".01">
                        </td>
                        <td class="total-field">
                            <input style="width: 200px" class="form-control-plaintext" readonly type="text"
                                data-id="t-{{ $registration->startnumber }}" step=".001">
                        </td>
                        <td><input type="checkbox" name="s[]" value="{{ $registration->startnumber }}"></td>
                    @endif
                </tr>
            @endforeach
        </form>
    </table>
    <a class="btn btn-primary" id="save-btn">Verwerken</a>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script>
        $(() => {
            $('#save-btn').on('click', function() {
                let ids = []
                $('.form-control-plaintext').each(function() {
                    ids.push($(this).attr('data-id').split('-')[1])
                })
                $('input[name=ids]').val(ids.toString())
                $('#scoreform').submit()
            })

            $('.form-control').on('focus', function() {
                if ($(this).val() == 0) {
                    $(this).val('')
                }
            })
            $('.form-control').on('blur', function() {
                if ($(this).val() == '') {
                    $(this).val(0)
                    var id = $(this).attr('name').split('-')[1]
                    put_total(id)
                }
            })
            $('.form-control').on('change', function() {
                var id = $(this).attr('name').split('-')[1]
                put_total(id)
            })

            function put_total(id) {
                var d = $(`[name='d-${id}']`).val()
                var e = $(`[name='e-${id}']`).val()
                var n = $(`[name='n-${id}']`).val()
                if (d == 0 && e == 0 && n == 0) {
                    var total = ''
                } else {
                    var total = Math.round(((parseFloat(d) + (10 - parseFloat(e))) - parseFloat(n)) * 1000) / 1000
                }
                $(`[data-id='t-${id}']`).val(total)
            }
        })
    </script>
@endsection

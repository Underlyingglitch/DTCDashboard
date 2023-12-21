@extends('layouts.app')

@section('page_title', 'Home')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h2>Actuele wedstrijdinformatie</h2>
            @if ($current_competition)
                <p>De huidige competitie is <a
                        href="{{ route('competitions.show', $current_competition) }}">{{ $current_competition->name }}</a>.
                </p>
            @else
                <p>Er is momenteel geen competitie geselecteerd.</p>
            @endif
            @if ($current_match_day)
                <p>De huidige/aankomende wedstrijd is op <a
                        href="{{ route('matchdays.show', $current_match_day) }}">{{ $current_match_day->date }}
                        in
                        {{ $current_match_day->location->name }}</a>.
                </p>
            @else
                <p>Er is momenteel geen wedstrijddag geselecteerd.</p>
            @endif
            @if ($current_wedstrijd)
                <p>De huidige wedstrijd is <a href="{{ route('matchdays.show', $current_wedstrijd) }}">Wedstrijd
                        {{ $current_wedstrijd->index }}</a> in
                    ronde {{ $current_round }}.
                </p>
            @else
                <p>Er is momenteel geen wedstrijd geselecteerd.</p>
            @endif
        </div>
        <div class="col-md-6">
            <h2>Wedstrijden</h2>
            <p>Op deze website vind je alle informatie over de wedstrijden die wij organiseren.</p>
            <p>Deze website is nog in ontwikkeling. Mocht je een fout tegenkomen, laat het mijn dan weten via <a
                    href="mailto:rickokkersen@gmail.com">rickokkersen@gmail.com</a>.</p>
        </div>
    </div>
@endsection

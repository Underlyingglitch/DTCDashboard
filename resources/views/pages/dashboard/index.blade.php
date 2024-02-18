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
                        href="{{ route('matchdays.show', $current_match_day) }}">{{ $current_match_day->date->format('d-m-Y') }}
                        in
                        {{ $current_match_day->location->name }}</a>.
                </p>
            @else
                <p>Er is momenteel geen wedstrijddag geselecteerd.</p>
            @endif
            @if ($current_wedstrijd)
                <p>De huidige wedstrijd is <a href="{{ route('wedstrijden.show', $current_wedstrijd) }}">Wedstrijd
                        {{ $current_wedstrijd->index }}</a> in
                    ronde {{ $current_round }}.
                </p>
            @else
                <p>Er is momenteel geen wedstrijd geselecteerd.</p>
            @endif
        </div>

        <div class="col-md-6">
            <h2>DTC Dashboard</h2>
            <div class="alert alert-primary">Deze website is nog in ontwikkeling. Mocht je een fout tegenkomen, laat dit mij
                dan weten via <a href="mailto:rickokkersen@gmail.com">rickokkersen@gmail.com</a>, of gebruik het feedback
                veld.</div>
            <form action="{{ route('feedback.store') }}" method="post">
                @csrf
                <textarea class="form-control" name="feedback" placeholder="Laat hier uw feedback achter" cols="30" rows="5"></textarea>
                <input type="submit" class="btn btn-primary" value="Insturen" />
            </form>
            @can('viewAll', \App\Models\Feedback::class)
                <h4>Feedback</h4>

                @foreach (\App\Models\Feedback::all() as $fb)
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('feedback.destroy', $fb) }}" method="post">
                                {{ $fb->user->name }}
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="float:right;color:red;background:none!important;border:none;padding:0!important;"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        </div>
                        <div class="card-body">
                            {{ $fb->feedback }}<br>

                        </div>
                        <div class="card-footer">
                            <small>{{ $fb->created_at->format('d-m-Y') }}</small>
                        </div>
                    </div>
                @endforeach
            @endcan
        </div>
    </div>
@endsection

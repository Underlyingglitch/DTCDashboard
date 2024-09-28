@extends('layouts.app')

@section('page_title', 'Wedstrijdplanning meldingen')

@section('content')
    <p>Op deze pagina kunnen de standaardinstellingen voor emailmeldingen bij wijzigingen in de wedstrijdplanning worden
        aangepast. Door het vinkje bij "Nieuwe wedstrijden" aan te zetten kan een verdere selectie gemaakt worden op
        district en discipline niveau. U krijgt dan een emailbericht bij nieuwe wedstrijden die in het aangegeven district
        (of districten) EN gekozen discipline(s) vallen.</p>
    <p>Door het vinkje bij "Wijzigingen in ALLE wedstrijden" aan te zetten kan eenzelfde selectie worden gemaakt. Let op:
        dit zal resulteren in veel emails. Het is aan te raden om in plaats daarvan te abonneren op specifieke wedstrijden
        in de wedstrijdplanning.</p>
    <p>Als er in een kolom geen selectie wordt gemaakt worden alle districten/disciplines meegenomen in de selectie.</p>
    <hr>
    <form action="{{ route('settings.calendar_updates') }}" method="post">
        @csrf
        <div class="form-group">
            <input type="checkbox" id="enabled_new" name="enabled_new" @if ($settings['enabled_new'] ?? false) checked @endif"
                data-action="toggleelement" data-action-id="new_selection">
            <label for="enabled_new">Nieuwe wedstrijden
                <span data-toggle="tooltip" title="Ontvang een melding bij nieuwe wedstrijden in geselecteerde categorieën">
                    <i class="fas fa-question-circle"></i>
                </span>
            </label>
        </div>

        <div class="container" id="new_selection" data-toggledby="enabled_new" style="display: none">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <b>District</b><br>
                        @foreach ($districts ?? [] as $district)
                            <input type="checkbox" id="new_districts_{{ $district }}" name="new_districts[]"
                                value="{{ $district }}" @if (in_array($district, $settings['new_districts'])) checked @endif">
                            <label for="new_districts_{{ $district }}">{{ $district }}</label>
                            <br>
                        @endforeach
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <b>Discipline</b><br>
                        @foreach ($disciplines ?? [] as $discipline)
                            <input type="checkbox" id="new_disciplines_{{ $discipline }}" name="new_disciplines[]"
                                value="{{ $discipline }}" @if (in_array($discipline, $settings['new_disciplines'])) checked @endif">
                            <label for="new_disciplines_{{ $discipline }}">{{ $discipline }}</label>
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <hr>

        <div class="form-group">
            <input type="checkbox" id="enabled_change" name="enabled_change"
                @if ($settings['enabled_change'] ?? false) checked @endif" data-action="toggleelement"
                data-action-id="change_selection">
            <label for="enabled_change">Wijzigingen in ALLE wedstrijden
                <span data-toggle="tooltip"
                    title="Ontvang een melding bij wijzigingen in ALLE wedstrijd in geselecteerde categorieën. Uitschakelen geeft de mogelijkheid om handmatig te abonneren op specifieke wedstrijden">
                    <i class="fas fa-question-circle"></i>
                </span>
            </label>
        </div>

        <div class="container" id="change_selection" data-toggledby="enabled_change" style="display: none">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <b>District</b><br>
                        @foreach ($districts ?? [] as $district)
                            <input type="checkbox" id="change_districts_{{ $district }}" name="change_districts[]"
                                value="{{ $district }}" @if (in_array($district, $settings['change_districts'])) checked @endif">
                            <label for="change_districts_{{ $district }}">{{ $district }}</label>
                            <br>
                        @endforeach
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <b>Discipline</b><br>
                        @foreach ($disciplines ?? [] as $discipline)
                            <input type="checkbox" id="change_disciplines_{{ $discipline }}" name="change_disciplines[]"
                                value="{{ $discipline }}" @if (in_array($discipline, $settings['change_disciplines'])) checked @endif">
                            <label for="change_disciplines_{{ $discipline }}">{{ $discipline }}</label>
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Opslaan</button>
    </form>
    <br>
    <div class="card">
        <div class="card-header">
            <h5>Abonnementen</h5>
        </div>
        <div class="card-body">
            @if (count($subscriptions) == 0)
                <p>U heeft momenteel geen abonnementen op specifieke wedstrijden</p>
            @else
                @foreach ($subscriptions as $subscription)
                    <div class="row">
                        <div class="col">
                            <h5>{{ $subscription->title }}</h5>
                            <small>{{ $subscription->discipline }} | {{ $subscription->district }}</small>
                        </div>
                        <div class="col-auto" style="width: 200px">{{ $subscription->place }}</div>
                        <div class="col-auto" style="width: 250px">{{ $subscription->date }}</div>
                        <div class="col-auto" style="width: 100px">
                            @if (
                                !is_null($subscription->description) ||
                                    !empty($subscription->description) ||
                                    count($subscription->description_files) != 0)
                                <span style="color: rgb(0, 166, 255)"><i class="fas fa-info-circle"></i></span>
                            @endif
                            @if (!is_null($subscription->program) || !empty($subscription->program) || count($subscription->program_files) != 0)
                                <span style="color: rgb(149, 0, 255)"><i class="fas fa-table-cells-large"></i></span>
                            @endif
                            @if (!is_null($subscription->results) || !empty($subscription->results) || count($subscription->results_files) != 0)
                                <span style="color: rgb(255, 179, 0)"><i class="fas fa-trophy"></i></span>
                            @endif
                        </div>
                        <div class="col-auto">
                            @livewire('calendar.subscribe-btn', ['id' => $subscription->id], key($subscription->id))
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <br>

@endsection

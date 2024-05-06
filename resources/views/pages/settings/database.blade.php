@extends('layouts.app')

@section('page_title', 'Database instelling')

@section('content')
    @if (Auth::user()->hasRole('admin'))
        @if (\App\Models\Setting::getValue('db_write_enabled'))
            <a class="btn btn-danger" href="{{ route('settings.set', ['db_write_enabled', 0]) }}">Uitschakelen</a>
            <a class="btn btn-warning" href="{{ route('settings.database.process') }}">Wijzigingen verwerken</a>
        @else
            <a class="btn btn-success" href="{{ route('settings.set', ['db_write_enabled', 1]) }}">Inschakelen</a>
        @endif
        @if (config('app.compare_database'))
            <a class="btn btn-primary" href="{{ route('settings.database.compare') }}">Databases vergelijken</a>
        @endif

    @endif

    <h4>Waarom zie ik deze melding?</h4>
    <p>
        Als je deze melding ziet, betekent dit dat er geen nieuwe gegevens kunnen worden opgeslagen in de database.
        Waarschijnlijk is er op dit moment een wedstrijd actief of is er onderhoud aan de database. Om te voorkomen dat
        wijzigingen dubbel worden opgeslagen worden alle wijzigingen tijdelijk opgeslagen. Als de database weer beschikbaar
        is zullen deze wijzigingen alsnog toegevoegd worden.
    </p>
    <p>
        Als je deze melding ziet terwijl er geen wedstrijd actief is, neem dan contact op met <a
            href="mailto:rickokkersen@gmail.com">rickokkersen@gmail.com</a>.
    </p>

    <h4>Mijn wijzigingen zijn niet verwerkt ondanks dat de database weer actief is</h4>
    <p>Als er conflicten waren in de data zijn wijzigingen niet verwerkt. Bewerken van data is alleen toegepast als deze
        data niet uit een andere bron is bewerkt. Neem voor meer informatie contact op met <a
            href="mailto:rickokkersen@gmail.com">rickokkersen@gmail.com</a>.</p>
@endsection

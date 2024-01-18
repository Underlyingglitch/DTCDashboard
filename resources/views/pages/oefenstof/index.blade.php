@extends('layouts.app')

@section('page_title', 'Oefenstof')

@section('content')
    @if (!$up_to_date)
        <div class="alert alert-warning">
            <p>
                {{-- TODO: Add message if user is subscribed to email notifications on this subject --}}
                De documenten op de website van Dutch Gymnastics zijn gewijzigd. Deze wijzigingen kunnen verschillen t.o.v.
                de oefenstof op deze pagina. <b>De oefenstof van Dutch Gymnastics is altijd leidend.</b> We doen ons best om
                deze pagina zo snel mogelijk bij te werken. Deze melding zal dan verdwijnen.
            </p>
            <a href="{{ route('dg_resources.index') }}">Bekijk deze wijzigingen</a>
        </div>
    @endif

    
@endsection

@section('page_title', $title)

<div>
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="alert-heading">Disclaimer</h4>
        <p>De scores op deze pagina kunnen vertraging hebben. In zeer uitzonderlijke gevallen kunnen de scores fouten
            bevatten. De gepubliceerde puntenlijsten zijn altijd leidend en aan de scores op deze pagina kunnen geen
            rechten
            worden
            ontleend.</p>
    </div>
    <button class="btn btn-success" id="enable-notifications">Sta meldingen toe</button>
    <div id="accordion">
        @foreach ($matchdays as $matchday)
            <a href="{{ route('livescores.show', [$matchday]) }}">
                <div class="card">
                    <div class="card-header text-center" id="heading{{ $matchday->id }}" aria-expanded="false">
                        <h5 class="mb-0">
                            {{ $matchday->date->format('d-m-Y') }} | {{ $matchday->location->name }}
                        </h5>
                        <small>{{ $matchday->competition->name }}</small>
                    </div>
                </div>
            </a>
        @endforeach

    </div>
</div>

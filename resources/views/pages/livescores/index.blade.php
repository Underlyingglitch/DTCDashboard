@extends('layouts.livescores')

@section('page_title', 'Livescores')

@section('content')
    <div class="alert alert-info">
        <h4 class="alert-heading">Disclaimer</h4>
        <p>De scores op deze pagina kunnen vertraging hebben. In zeer uitzonderlijke gevallen kunnen de scores fouten
            bevatten. De gepubliceerde puntenlijsten zijn altijd leidend en aan deze scores kunnen geen rechten worden
            ontleend.</p>
    </div>
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
@endsection

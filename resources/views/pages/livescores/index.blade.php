@extends('layouts.app')

@section('page_title', 'Livescores')

@section('content')
    {{-- @livewire('livescores.teams')

    @livewire('livescores.individual') --}}
    <i>Aan deze functie wordt momenteel hard gewerkt. </i>
    <div id="accordion">
        @foreach ($matchdays as $matchday)
            <div class="card">
                <div class="card-header {{ !$loop->first ? 'collapsed' : '' }} text-center" id="heading{{ $matchday->id }}"
                    data-toggle="collapse" data-target="#collapse{{ $matchday->id }}"
                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $matchday->id }}">
                    <h5 class="mb-0">
                        {{ $matchday->date->format('d-m-Y') }} | {{ $matchday->location->name }}
                    </h5>
                    <small>{{ $matchday->competition->name }}</small>
                </div>

                <div id="collapse{{ $matchday->id }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                    aria-labelledby="heading{{ $matchday->id }}" data-parent="#accordion">
                    <div class="card-body">
                        @foreach ($matchday->niveaus as $niveau)
                            <button class="btn btn-primary form-control">{{ $niveau->full_name }}</button><br>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

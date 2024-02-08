@extends('layouts.livescores')

@section('page_title', 'Livescores')

@section('content')
    {{-- @livewire('livescores.teams')

    @livewire('livescores.individual') --}}
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

@extends('layouts.livescores')

@section('page_title', 'Livescores ' . $niveau->full_name)

@section('content')
    {{-- @livewire('livescores.teams')

    @livewire('livescores.individual') --}}
    <div class="mt-4 card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request()->tab == 'teams' ? '' : 'active' }}"
                        href="{{ route('livescores.show', [$matchday, $niveau]) }}">Individueel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (count($teams) == 0) disabled @endif {{ request()->tab == 'teams' ? 'active' : '' }}"
                        @if (count($teams) != 0) href="{{ route('livescores.show', [$matchday, $niveau, 'tab' => 'teams']) }}" @endif>Teams</a>
                </li>
            </ul>
        </div>
        @if (request()->tab == 'teams')
            @livewire('livescores.teams', ['matchday' => $matchday->id, 'niveau' => $niveau->id], key($matchday->id . $niveau->id))
        @else
            @livewire('livescores.individual', ['matchday' => $matchday->id, 'niveau' => $niveau->id], key($matchday->id . $niveau->id))
        @endif
    </div>

@endsection

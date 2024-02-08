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

@section('nav')
    <nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarCollapse">
            <ul class="navbar-nav">
                @foreach ($matchday->niveaus as $n)
                    <li class="nav-item">
                        <a class="nav-link @if ($n->id == $niveau->id) active @endif"
                            href="{{ route('livescores.show', [$matchday, $n]) }}">{{ $n->full_name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
@endsection

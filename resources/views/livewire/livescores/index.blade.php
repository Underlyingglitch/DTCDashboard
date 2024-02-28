<div>
    <div class="container" style="padding-bottom: 60px;">
        <div class="mt-4 card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if ($page == 'individual') active @endif"
                            wire:click="tab('individual')">Individueel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link
                            @if ($page == 'teams') active @elseif(!$teams) disabled @endif"
                            @if ($teams) wire:click="tab('teams')" @endif>Teams</a>
                    </li>
                </ul>
            </div>
            {{-- Content here --}}
            @if ($page == 'individual')
                @livewire('livescores.individual', ['matchday' => $matchday, 'niveau' => $niveau], key($page . $niveau))
            @else
                @livewire('livescores.teams', ['matchday' => $matchday, 'niveau' => $niveau], key($page . $niveau))
            @endif
        </div>

        {{-- @yield('content') --}}
    </div>
    <nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarCollapse">
            <ul class="navbar-nav">
                @foreach ($niveaus as $n)
                    <li class="nav-item">
                        <a class="nav-link @if ($n->id == $niveau) active @endif"
                            wire:click="setNiveau({{ $n->id }})">{{ $n->full_name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</div>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">DTC Zuid</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-house"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Informatie
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('dg_resources.index') }}">
            <i class="fas fa-fw fa-file-lines"></i>
            <span>KNGU Bronnen</span></a>
    </li>

    @can('viewAny', \App\Models\CalendarItem::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('calendar.index') }}">
                <i class="fas fa-fw fa-calendar"></i>
                <span>Wedstrijdplanning</span></a>
        </li>
    @endcan

    {{-- <li class="nav-item">
        <a class="nav-link" href="{{ route('oefenstof.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Oefenstof</span></a>
    </li> --}}

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Scoresysteem
    </div>

    <li class="nav-item">
        <a class="nav-link" target="_blank" href="{{ route('livescores.index') }}">
            <i class="fas fa-fw fa-sliders"></i>
            <span>Livescores</span></a>
    </li>

    @can('viewAny', App\Models\Competition::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('competitions.index') }}">
                <i class="fas fa-fw fa-list"></i>
                <span>Competities</span></a>
        </li>
    @endcan

    @can('viewAny', App\Models\Location::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('locations.index') }}">
                <i class="fas fa-fw fa-map-marker"></i>
                <span>Locaties</span></a>
        </li>
    @endcan

    @can('viewAny', App\Models\Club::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clubs.index') }}">
                <i class="fas fa-fw fa-list"></i>
                <span>Verenigingen</span></a>
        </li>
    @endcan

    @if (Auth::user()->can('viewAny', App\Models\Gymnast::class) ||
            Auth::user()->can('viewAny', App\Models\Trainer::class) ||
            Auth::user()->can('viewAny', App\Models\Jury::class))
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePeople"
                aria-expanded="true" aria-controls="collapsePeople">
                <i class="fas fa-fw fa-users"></i>
                <span>Personen</span>
            </a>
            <div id="collapsePeople" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('viewAny', App\Models\Gymnast::class)
                        <a class="collapse-item" href="{{ route('gymnasts.index') }}">Turners</a>
                    @endcan
                    @can('viewAny', App\Models\Trainer::class)
                        <a class="collapse-item" href="{{ route('trainers.index') }}">Trainers</a>
                    @endcan
                    @can('viewAny', App\Models\Jury::class)
                        <a class="collapse-item" href="{{ route('juries.index') }}">Juryleden</a>
                    @endcan
                </div>
            </div>
        </li>
    @endif

    @if (Auth::user()->hasRole('admin'))
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            Administratie
        </div>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('settings.index') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Instellingen</span></a>
        </li>

        @if (config('app.env') == 'local' || config('app.env') == 'dev')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('monitor.index') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Monitor</span></a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Gebruikers</span></a>
        </li>
    @endcan

    {{-- <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li> --}}

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

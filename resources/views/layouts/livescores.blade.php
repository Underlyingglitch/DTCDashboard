<html lang="en" data-bs-theme="light">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title')</title>

    <script>
        window.userId = @json(auth()->id());
    </script>
    @vite(['resources/scss/livescores.scss', 'resources/js/app.js'])

    <script type="module" defer>
        let matchday = @json($matchday->id);
        let online_count = document.getElementById('online_count');
        let count = 0;
        window.Echo.join(`livescores.${matchday}`)
            .here((users) => {
                console.log('here', users)
                count = users.length;
                online_count.textContent = count;
            })
            .joining((user) => {
                console.log('joining', user)
                count++;
                online_count.textContent = count;
            })
            .leaving((user) => {
                console.log('leaving', user)
                count--;
                online_count.textContent = count;
            })
    </script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    @livewireStyles()

</head>

<body>
    <div class="pt-2 pl-2">
        <span class="badge badge-success" id="online_count">0</span> Gebruiker(s) online
    </div>
    <div class="container" style="padding-bottom: 60px;">
        {{-- Create a x number of users online indicator --}}

        @yield('content')
    </div>
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
    @livewireScripts()
</body>

</html>

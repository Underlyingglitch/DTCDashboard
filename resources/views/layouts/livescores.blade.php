<html lang="en" data-bs-theme="light">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title')</title>

    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

    <script>
        window.userId = @json(auth()->id());
    </script>
    @vite(['resources/scss/livescores.scss', 'resources/js/app.js'])

    <script type="module" defer>
        let online_count = document.getElementById('online_count');
        let count = 0;
        window.Echo.join(`livescores`)
            .here((users) => {
                count = users.length;
                updateCounter(count);
            })
            .joining((user) => {
                count++;
                updateCounter(count);
            })
            .leaving((user) => {
                count--;
                updateCounter(count);
            })

        function updateCounter(c) {
            online_count.textContent = c;
            online_text.textContent = c == 1 ? 'Gebruiker online' : 'Gebruikers online';
        }
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
        <span class="badge badge-success" id="online_count">0</span> <span id="online_text">Gebruikers online</span>
    </div>
    {{ $slot }}
    @livewireScripts()
</body>

</html>

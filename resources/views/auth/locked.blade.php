<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

    <title>Vergrendeld - DTC Zuid</title>

    @vite(['resources/js/app.js', 'resources/scss/app.scss'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">
    <script type="module" defer>
        window.axios.post('/api/internal/ping', {
            page: window.location.pathname,
            user_id: null
        }).then((data) => {
            let id = data.data.id
            loadPage(data.data.loaded_page)
            window.Echo.channel(`monitor.${id}`).listen('.DeviceUpdated', (e) => {
                loadPage(e.loaded_page)
            })
        }).catch((error) => {
            console.log(error)
        })

        function loadPage(page) {
            if (page == window.location.pathname) return
            window.location.pathname = page
        }
    </script>

</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4">
                            <h1 class="h2">Scherm vergrendeld</h1>
                            <p class="lead">
                                Log in om verder te gaan
                            </p>
                        </div>

                        @if (Session::has('success'))
                            <div class="alert alert-success">{!! Session::get('success') !!}</div>
                        @endif
                        @error('details')
                            <div class="alert alert-danger" role="alert">
                                <span>{{ $message }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @enderror

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <form action="{{ route('auth.login') }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="has-validation">
                                                <label class="form-label">Wachtwoord</label>
                                                <input
                                                    class="form-control @if ($errors->any()) @error('password') is-invalid @else is-valid @enderror @endif form-control-lg"
                                                    type="password" name="password" placeholder="Wachtwoord">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Ontgrendelen</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            Niet uw account? <a href="{{ route('auth.logout') }}">Uitloggen</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



</body>

</html>

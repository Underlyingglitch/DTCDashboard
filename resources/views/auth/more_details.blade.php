<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

    <title>Registreren - DTC Zuid</title>

    @vite(['resources/js/app.js', 'resources/scss/app.scss'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">

</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4">
                            <h1 class="h2">Welkom!</h1>
                            <p class="lead">
                                Maak een account aan om verder te gaan
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
                                    <form action="{{ route('auth.more_details') }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="has-validation">
                                                <label class="form-label">Vereniging</label>
                                                <input
                                                    class="form-control @if ($errors->any()) @error('club') is-invalid @else is-valid @enderror @endif form-control-lg"
                                                    type="text" name="club" placeholder="Naam vereniging">
                                                @error('club')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="has-validation">
                                                <label class="form-label">Type account</label>
                                                <select class="form-control" name="type">
                                                    <option value="--">Selecteer type</option>
                                                    <option value="jury">Jurylid</option>
                                                    <option value="trainer">Trainer</option>
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Registratie
                                                voltooien</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



</body>

</html>

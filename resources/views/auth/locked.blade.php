<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Vergrendeld - DTC TH Zuid</title>

    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

    <script src="/config.js"></script>
    @php($vite = ['resources/scss/app.scss', 'resources/js/app.js'])
    @if (env('APP_ENV') == 'local' || env('APP_ENV') == 'dev')
        @php($vite[] = 'resources/js/localLogin.js')
    @endif
    @vite($vite)
</head>

<body class="login-background">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-6">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Scherm vergrendeld</h1>
                                    </div>
                                    @error('details')
                                        <div class="alert alert-danger" role="alert">
                                            {{ $message }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @enderror
                                    <form class="user" action="{{ route('login') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <input type="password"
                                                class="form-control form-control-user @if ($errors->any()) @error('password') is-invalid @enderror @endif"
                                                placeholder="Wachtwoord" name="password">
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Ontgrendelen
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="btn btn-secondary" href="{{ route('auth.logout') }}">Uitloggen</a>
                                        {{-- <br>of<br>
                                        <a class="btn-link small" href="#">Wachtwoord vergeten?</a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

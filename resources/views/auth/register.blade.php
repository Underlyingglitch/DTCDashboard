<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Registreren - DTC Zuid</title>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
                                        <h1 class="h4 text-gray-900 mb-4">Welkom!</h1>
                                    </div>
                                    @error('details')
                                        <div class="alert alert-danger" role="alert">
                                            {{ $message }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @enderror
                                    <form class="user" action="{{ route('auth.login') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text"
                                                class="form-control form-control-user @if ($errors->any()) @error('name') is-invalid @else is-valid @enderror @endif"
                                                placeholder="Naam" name="name" value="{{ old('name') }}">
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <div class="form-group">
                                            <input type="text"
                                                class="form-control form-control-user @if ($errors->any()) @error('email') is-invalid @else is-valid @enderror @endif"
                                                placeholder="Email adres" name="email" value="{{ old('email') }}">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

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
                                            Registreren
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        Voor meer informatie, neem contact op met <a
                                            href="mailto:rickokkersen@gmail.com">rickokkersen@gmail.com</a>.
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

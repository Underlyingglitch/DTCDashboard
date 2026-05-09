<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Device Authentication - DTC TH Zuid</title>

    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

    <script src="/config.js"></script>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
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
                                        <h1 class="h4 text-gray-900 mb-4">Device Authentication</h1>
                                        <p class="text-gray-600 mb-4">Enter your device token to authenticate.</p>
                                    </div>
                                    @if (Session::has('success'))
                                        <div class="alert alert-success">{!! Session::get('success') !!}</div>
                                    @endif
                                    @error('token')
                                        <div class="alert alert-danger" role="alert">
                                            {{ $message }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @enderror
                                    <form class="user" action="{{ route('auth.device.login') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text"
                                                class="form-control form-control-user @if ($errors->any()) @error('token') is-invalid @else is-valid @enderror @endif"
                                                placeholder="Authentication Token" name="token"
                                                value="{{ request('token', old('token')) }}" autofocus>
                                        </div>
                                        @error('token')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Authenticate Device
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
</body>

</html>

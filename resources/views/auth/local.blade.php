<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Local - DTC TH Zuid</title>

    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

    <script src="/config.js"></script>

    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/localLogin.js'])
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
                                    @if (Session::has('error'))
                                        <div class="alert alert-danger">{!! Session::get('error') !!}</div>
                                    @endif
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Wachten op authorisatie...</h1>
                                        <h1 class="h4 text-gray-900">Code: <span id="device_code"></h1>
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>DTC Zuid</title>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="login-background">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    @auth
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900">Welkom, {{ Auth::user()->name }}!</h1>
                                            <a class="btn btn-sm btn-danger mb-4"
                                                href="{{ route('auth.logout') }}">Uitloggen</a>
                                            <a class="btn btn-sm btn-primary mb-4" href="{{ route('dashboard') }}">Naar
                                                het dashboard</a>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Welkom!</h1>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <a class="btn btn-sm btn-success mb-4"
                                                href="{{ route('auth.login') }}">Inloggen</a>
                                            <p>&nbsp;&nbsp;of&nbsp;&nbsp;</p>
                                            <a class="btn btn-sm btn-primary mb-4"
                                                href="{{ route('auth.register') }}">Registreren</a>
                                        </div>
                                    @endauth


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

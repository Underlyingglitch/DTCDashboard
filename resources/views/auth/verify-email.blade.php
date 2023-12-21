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
                                        <h1 class="h4 text-gray-900 mb-4">Verifieer uw emailadres!</h1>
                                    </div>
                                    @if (Session::has('success'))
                                        <div class="alert alert-success">{!! Session::get('success') !!}</div>
                                    @else
                                        <div class="alert alert-warning">
                                            Voordat u kunt inloggen moet u uw emailadres verifieren. U heeft hiervoor
                                            een
                                            link ontvangen op het door u opgegeven emailadres.<br>
                                            <a href="{{ route('verification.send') }}">Opnieuw versturen</a>
                                        </div>
                                    @endif

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

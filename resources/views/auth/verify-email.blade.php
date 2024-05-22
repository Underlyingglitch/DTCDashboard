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
                            <h1 class="h2">Verifieer uw emailadres!</h1>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
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
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            Heeft u vragen? <a href="mailto:rickokkersen@gmail.com">rickokkersen@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



</body>

</html>

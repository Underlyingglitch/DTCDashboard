<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>DTC Zuid</title>

    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

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
                                    <div class="alert alert-info">
                                        Deze website is nog in ontwikkeling, maar een aantal functies zijn al
                                        beschikbaar. Bij vragen en/of opmerkingen kunt u contact opnemen met <a
                                            href="mailto:rickokkersen@gmail.com">rickokkersen@gmail.com</a>
                                    </div>
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
                                            <h1 class="h4 text-gray-900 mb-4">Welkom op de website van DTC Zuid!</h1>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <a class="btn btn-sm btn-success mb-4"
                                                href="{{ route('auth.login') }}">Inloggen</a>
                                            <p>&nbsp;&nbsp;of&nbsp;&nbsp;</p>
                                            <a class="btn btn-sm btn-primary mb-4"
                                                href="{{ route('auth.register') }}">Registreren</a>
                                        </div>
                                        <div>
                                            <b>Inloggen en registreren</b><br>
                                            Trainers kunnen een account registreren met het emailadres dat bekend is bij de
                                            KNGU. Juryleden kunnen registreren met een emailadres dat bekend is binnen het
                                            district. <br>
                                            Indien je een account met een bekend emailadres registreert, zal je account
                                            automatisch worden goedgekeurd. Na verificatie van het emailadres kun je dan
                                            inloggen<br>
                                            Indien je een account met een onbekend emailadres registreert, zal je account
                                            handmatig moeten worden goedgekeurd. Dit kan enkele dagen duren. <br>
                                            <b>LET OP: </b> Momenteel krijgen alleen trainers en juryleden van ons district
                                            toegang. Dit zal later worden uitgebreid.
                                            <br><br>
                                            <b>Functionaliteiten</b> (schuingedrukte items zijn nog in ontwikkeling)<br>
                                            <u>Overzicht van alle competities sinds 2021</u><br>
                                            - Overzicht van alle wedstrijden<br>
                                            - Overzicht van alle deelnemers<br>
                                            - Overzicht van de groeps- en teamindelingen<br>
                                            - <i>Overzicht van de uitslagen</i><br>
                                            - <i>Overzicht van de doorstroming</i><br>
                                            <br>
                                            <u>Livescores</u><br>
                                            De livescores zijn momenteel nog niet beschikbaar. Deze functie is nog in
                                            ontwikkeling<br>
                                            <br>
                                            <u>Bronnenoverzicht van de KNGU website</u><br>
                                            - Overzicht van alle documenten en links op de KNGU website (Oefenstof en
                                            reglementen)<br>
                                            - Indicatie of het document nieuw is (toegevoegd in de laatste 7 dagen)<br>
                                            - Indicatie of het document is gewijzigd (gewijzigd in de laatste 7 dagen)<br>
                                            - Indicatie of het document is verwijderd (het document blijft dan te downloaden
                                            via deze website)<br>
                                            - <i>Mogelijkheid om een email te krijgen bij gewijzigde/toegevoegde
                                                documenten</i>

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

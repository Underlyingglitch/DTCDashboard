<html>

<head>
    @vite('resources/sass/pdf.scss')
    <title>Groepsindeling W{{ $wedstrijd->index }} - {{ $wedstrijd->match_day->location->name }}</title>
</head>

<body>
    <header>
        <img class="header-img" src="{{ asset('img/kngu_header.png') }}" alt="">
        <h2 class="title">{{ $wedstrijd->competition->name }}</h2>
        <h2 class="subtitle">Locatie: {{ $wedstrijd->match_day->location->name }}</h2>
        <p><a class="no-print" href="{{ route('wedstrijden.export.groups', $wedstrijd->id - 1) }}">
                &lArr;</a> Wedstrijd {{ $wedstrijd->index }} | {{ $wedstrijd->niveaus_list }} <a class="no-print"
                href="{{ route('wedstrijden.export.groups', $wedstrijd->id + 1) }}">&rArr;</a>
        </p>
    </header>

    <main>
        <table class="group-table">
            <tr>
                <th colspan="3">{Teamname}</th>
                @foreach ($toestellen as $toestel)
                    <th>{{ $toestel }}</th>
                @endforeach
            </tr>
            <tr>
                <td style="width: 5%">1</td>
                <td style="width: 10%">{Gymnast}</td>
                <td style="width: 25%">{Club}</td>
                @foreach ($toestellen as $toestel)
                    <td style="width: 10%">{Score}</td>
                @endforeach
            </tr>
            <tr>
                <td style="width: 5%">2</td>
                <td style="width: 15%">{Gymnast}</td>
                <td style="width: 25%">{Club}</td>
                @foreach ($toestellen as $toestel)
                    <td style="width: 10%">{Score}</td>
                @endforeach
            </tr>
            <tr>
                <td colspan=2></td>
                <td>Totaal: {total}</td>
                @foreach ($toestellen as $toestel)
                    <td style="width: 10%">{Score}</td>
                @endforeach
            </tr>
        </table>
    </main>

</body>

</html>

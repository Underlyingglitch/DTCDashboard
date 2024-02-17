<html>

<head>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 0
        }

        header .header-img {
            height: 150px;
            position: absolute;
            z-index: -1
        }

        header h2 {
            text-align: center;
            font-size: 20px
        }

        .title {
            padding-top: 20px;
            margin-bottom: 0
        }

        .subtitle {
            margin-top: 5px;
            margin-bottom: 0
        }

        header p {
            text-align: center;
            font-size: 15px;
            margin-top: 0
        }

        table,
        td {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        main {
            margin: 20px
        }

        main .group-table {
            width: 100%;
            margin-bottom: 20px
        }

        main .group-table td,
        main .group-table th {
            padding: 3px;
            text-align: left;
            font-size: 10px
        }

        main .score-table {
            table-layout: fixed;
            width: 100%;
            margin-bottom: 20px
        }

        main .score-table td,
        main .score-table th {
            padding: 3px;
            text-align: left;
            font-size: 10px
        }

        .not-counted {
            font-style: italic;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important
            }
        }

        @page {
            size: auto;
            margin: 20mm 0 20mm 0
        }

        @page :first {
            margin-top: 0
        }
    </style>
    <title>@yield('title')</title>
</head>

<body>
    <header>
        @yield('header')
    </header>
    <main>
        @yield('main')
    </main>

</body>

</html>

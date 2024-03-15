<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="{{ asset('images/icons/icon128x128.png') }}">

    <title>@yield('page_title') | DTC Zuid</title>

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <script>
        window.userId = @json(auth()->id());
    </script>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @yield('scripts')
    <script type="module" defer>
        // Periodically send a ping to the server to keep the session alive
        setInterval(() => {
            window.axios.post('/api/internal/ping', {
                page: window.location.pathname,
                user_id: window.userId,
            })
        }, 1000 * 5);
        window.axios.post('/api/internal/ping', {
            page: window.location.pathname,
            user_id: window.userId,
        }).then((data) => {
            let id = data.data.id
            loadPage(data.data.loaded_page)
            window.Echo.channel(`monitor.${id}`).listen('.DeviceUpdated', (e) => {
                loadPage(e.loaded_page)
            })
        }).catch((error) => {
            console.log(error)
        })

        function loadPage(page) {
            if (page == window.location.pathname) return
            window.location.pathname = page
        }
    </script>
    @livewireStyles
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column" style="height: 100vh">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <x-topbar-jury />
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
                    </div>
                    @if (Session::has('success'))
                        <div class="alert alert-success">{!! Session::get('success') !!}</div>
                    @endif
                    @if (Session::has('warning'))
                        <div class="alert alert-warning">{!! Session::get('warning') !!}</div>
                    @endif
                    @if (Session::has('info'))
                        <div class="alert alert-info">{!! Session::get('info') !!}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Content Row -->
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Rick Okkersen {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
    </div>

    @livewireScripts
</body>

</html>

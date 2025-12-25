<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/main/img/icon.png') }}">
    <link rel="icon" href="{{ asset('assets/main/img/icon.png') }}">
    <title>@yield('title')</title>
    <!-- Stylesheet -->
        <!-- fa icon -->
            <link href="{{ asset('assets/soft-ui/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />
        <!-- ======= -->
        <!-- main -->
            <link id="pagestyle" href="{{ asset('assets/main/css/font.css?v=1.1.0') }}" rel="stylesheet" />
        <!-- ======= -->
    <!-- ========== -->
</head>
<body class="">
    <main class="main-content  mt-0">
        <section>
            @yield('content')
        </section>
    </main>
    <!-- core JS -->
        <script src="{{ asset('assets/soft-ui/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('assets/soft-ui/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/soft-ui/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/soft-ui/js/plugins/smooth-scrollbar.min.js') }}"></script>
        <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
        </script>
    <!-- ======= -->

</body>
</html>
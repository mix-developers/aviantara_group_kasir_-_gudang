<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ $title ?? 'Homepage' }} - {{ env('APP_NAME') }}</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend_theme/') }}/assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('frontend_theme/') }}/css/styles.css" rel="stylesheet" />
    @stack('css')
    <style>
        header {
            position: relative;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(105, 108, 255, 0.9);
            /* Lapisan warna dengan transparansi */
            z-index: 1;
            /* Overlay di atas gambar */
        }

        header .overlay {
            position: relative;
            z-index: 2;
            /* Konten di atas overlay */
        }

        header .top {
            position: relative;
            z-index: 3;
            /* Konten di atas overlay */
        }
    </style>
</head>

<body>
    @include('layouts.frontend.navbar')
    @yield('content')
    @include('layouts.frontend.footer')
    <!-- Footer-->

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('frontend_theme/') }}/js/scripts.js"></script>
</body>

</html>

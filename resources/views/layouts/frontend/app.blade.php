<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Primary Meta Tags -->
    <title>Pusat Grosir Frozen Food & Dry Food - Aviantara Group</title>
    <meta name="title" content="Pusat Grosir Frozen Food & Dry Food - Aviantara Group" />
    <meta name="description"
        content="Aviantara Group merauke menyediakan berbagai macam produk frozen food dan dry food berkualitas untuk kebutuhan restoran, toko, dan usaha Anda. Hubungi kami untuk penawaran grosir terbaik." />

    <!-- Keywords -->
    <meta name="keywords"
        content="Aviantara Group, aviantar group merauke, pusat grosir papua selatan, pusat grosir merauke, frozen food merauke, dry food merauke, distributor makanan beku, supplier makanan kering, grosir frozen food, grosir dry food, makanan beku, makanan kering, distributor makanan, grosir makanan merauke, merauke, papua selatan, pusat merauke, pusat papua selatan" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="Pusat Grosir Frozen Food & Dry Food - Aviantara Group" />
    <meta property="og:description"
        content="Aviantara Group menyediakan produk frozen food & dry food terbaik dengan harga grosir. Kami siap memenuhi kebutuhan bisnis Anda dengan produk berkualitas." />
    <meta property="og:image" content="{{ asset('img/') }}/logo.png" />

    <!-- Twitter -->
    <meta property="twitter:card" content="{{ asset('img/') }}/logo.png" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="Pusat Grosir Frozen Food & Dry Food - Aviantara Group" />
    <meta property="twitter:description"
        content="Aviantara Group adalah distributor dan supplier utama untuk frozen food dan dry food dengan penawaran grosir terbaik." />
    <meta property="twitter:image" content="{{ asset('img/') }}/logo.png" />

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/') }}/logo.png" />
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

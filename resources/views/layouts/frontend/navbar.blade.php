<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <!-- Navbar Brand -->
        <a class="navbar-brand fw-bold mx-auto d-lg-none text-center" href="#!">
            <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 10%" class="mx-2">
            <span class="text-warning">Aviantara</span>
            <span style="font-size: 12px;">Gruop</span>
        </a>

        <!-- Navbar content hidden on small screens -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <a class="navbar-brand fw-bold d-none d-lg-block" href="#!">
                <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 5%" class="mx-2">
                <span class="text-warning">Aviantara</span>
                <span style="font-size: 12px;">Gruop</span>
            </a>

            <!-- Right-side links -->
            <div class="d-flex justify-content-center w-100">
                @guest
                    <a class="btn btn-outline-dark me-2 my-2 my-lg-0" href="{{ route('login') }}" style="width: 100px;">
                        <i class="bi-people me-1"></i>
                        Login
                    </a>
                    <a class="btn btn-outline-dark mx-2 my-2 my-lg-0" href="{{ url('invoice') }}" style="width: 150px;">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        Cek Invoice
                    </a>
                @else
                    <a class="btn btn-outline-dark my-2 my-lg-0" href="{{ url('/home') }}" style="width: 200px;">
                        <i class="bi-speedometer me-1"></i>
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>
<!-- Header-->
<header class=" py-2" style="background-color: #696cff;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 20%">
            <h1 class="display-4 fw-bolder">AVIANTARA GROUP</h1>
            <p class="lead fw-normal text-white-50 mb-0">Pusat Grosir Frozen Food dan Dry Food</p>
        </div>
    </div>
</header>

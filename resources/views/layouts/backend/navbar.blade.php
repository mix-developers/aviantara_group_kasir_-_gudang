<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-home fs-4 lh-0"></i>
                <span class="fw-bold text-uppercase" style="margin-left:10px;">{{ Auth::user()->role }} AVIANTARA
                    GROUP</span>

            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item mx-2">
                <a class="nav-link" id="fullscreen-btn" href="javascript:void(0);" title="Full Screen">
                    <span class="position-relative">
                        <i class="bx bx-fullscreen bx-sm"></i>
                    </span>
                </a>
            </li>
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (Auth::user()->avatar == null || Auth::user()->name == '')
                            <span
                                class="avatar-initial rounded-circle bg-label-primary">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        @else
                            <img src="{{ Auth::user()->avatar != null || Auth::user()->avatar != '' ? url(Storage::url(Auth::user()->avatar)) : asset('/img/user.png') }}"
                                alt class="w-px-40 h-40 rounded-circle" style="object-fit: cover;" />
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if (Auth::user()->name == null || Auth::user()->name == '')
                                            <span
                                                class="avatar-initial rounded-circle bg-label-primary">{{ substr(Auth::user()->name, 0, 2) }}
                                            </span>
                                        @else
                                            <img src="{{ Auth::user()->avatar != null || Auth::user()->avatar != '' ? url(Storage::url(Auth::user()->avatar)) : asset('/img/user.png') }}"
                                                alt class="w-px-40 h-40 rounded-circle" style="object-fit: cover;" />
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->role ?? 'role' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('home') }}">
                            <i class="bx bx-home me-2"></i>
                            <span class="align-middle">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2 text-danger"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
@push('js')
    <script>
        const content = document.getElementById("content-fullscreen");
        const fullscreenBtn = document.getElementById("fullscreen-btn");

        fullscreenBtn.addEventListener("click", () => {
            if (!document.fullscreenElement) {
                // Masuk mode fullscreen
                content.requestFullscreen().catch((err) => {
                    alert(`Error: ${err.message}`);
                });
                fullscreenBtn.innerHTML = '<i class="bx bx-exit-fullscreen bx-sm"></i>';
            } else {
                // Keluar dari mode fullscreen
                document.exitFullscreen().catch((err) => {
                    alert(`Error: ${err.message}`);
                });
                fullscreenBtn.innerHTML = '<i class="bx bx-fullscreen bx-sm"></i>';
            }
        });

        // Update tombol jika pengguna keluar fullscreen melalui cara lain
        document.addEventListener("fullscreenchange", () => {
            if (!document.fullscreenElement) {
                fullscreenBtn.innerHTML = '<i class="bx bx-fullscreen bx-sm"></i>';
            }
        });
    </script>
@endpush

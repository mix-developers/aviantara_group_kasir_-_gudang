<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        {{-- logo aplikasi --}}
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo text-center">
                <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 10%">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ env('APP_NAME') ?? 'Laravel' }}</span>
        </li>
        <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
            <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        @if (Auth::user()->role == 'Admin')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Data Master</span>
            </li>
            <li class="menu-item {{ request()->is('products*') ? 'active' : '' }}">
                <a href="{{ url('/products') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Produk</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('customers*') ? 'active' : '' }}">
                <a href="{{ url('/customers') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Pelanggan</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('paymentMethod*') ? 'active' : '' }}">
                <a href="{{ url('/paymentMethod') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-credit-card"></i>
                    <div data-i18n="Analytics">Metode Pembayaran</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('users*') ? 'active' : '' }}">
                <a href="{{ url('/users') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Pegawai</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Gudang & Toko</span>
            </li>
            <li class="menu-item {{ request()->is('wirehouses*') ? 'active' : '' }}">
                <a href="{{ url('/wirehouses') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home"></i>
                    <div data-i18n="Analytics">Gudang</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('shops*') ? 'active' : '' }}">
                <a href="{{ url('/shops') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div data-i18n="Analytics">Toko</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengelolaan Harga</span>
            </li>
            <li class="menu-item {{ request()->is('prices') ? 'active' : '' }}">
                <a href="{{ url('/prices') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Analytics">Harga Grosir</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan</span>
            </li>
            <li class="menu-item {{ request()->is('report/income') ? 'active' : '' }}">
                <a href="{{ url('/report/income') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Pendapatan</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('report/wirehouses') ? 'active' : '' }}">
                <a href="{{ url('/report/wirehouses') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Transaksi Gudang</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('report/shops') ? 'active' : '' }}">
                <a href="{{ url('/report/shops') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics"> Transaksi Toko</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('report/damaged') ? 'active' : '' }}">
                <a href="{{ url('/report/damaged') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics"> Produk Rusak</div>
                </a>
            </li>
        @elseif (Auth::user()->role == 'Gudang' && Auth::user()->id_wirehouse != null)
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengelolaan Stok Gudang</span>
            </li>
            <li class="menu-item {{ request()->is('products*') ? 'active' : '' }}">
                <a href="{{ url('/products') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Produk</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('stoks*') ? 'active' : '' }}">
                <a href="{{ url('/stoks') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-archive"></i>
                    <div data-i18n="Analytics">Input Stok Gudang</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('damageds') ? 'active' : '' }}">
                <a href="{{ url('/damageds') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trash"></i>
                    <div data-i18n="Analytics">Stok Rusak</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi Gudang</span>
            </li>
            <li class="menu-item {{ request()->is('order_wirehouses') ? 'active' : '' }}">
                <a href="{{ url('/order_wirehouses') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Input Pesanan</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('payments') ? 'active' : '' }}">
                <a href="{{ url('/payments') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Analytics">Pembayaran & Tagihan</div>
                </a>
            </li>
        @elseif (Auth::user()->role == 'Kasir' && Auth::user()->id_shop != null)
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi Kios</span>
            </li>
            <li class="menu-item {{ request()->is('kios_stok') ? 'active' : '' }}">
                <a href="{{ url('/kios_stok') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Analytics">Stok Kios</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('transaksi-kios') ? 'active' : '' }}">
                <a href="{{ url('/transaksi-kios') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Analytics">Transaksi Kios</div>
                </a>
            </li>
        @endif
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Akun</span>
        </li>
        <li class="menu-item {{ request()->is('profile') ? 'active' : '' }}">
            <a href="{{ url('/profile') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Profile</div>
            </a>
        </li>

    </ul>
</aside>

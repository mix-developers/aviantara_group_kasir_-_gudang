<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        {{-- logo aplikasi --}}
        <a href="{{ url('/') }}" class="app-brand-link">
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
        <li class="menu-item {{ request()->is('home') ? 'active' : '' }}">
            <a href="{{ url('/home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Owner')
            <li class="menu-item {{ request()->is('dashboard2') ? 'active' : '' }}">
                <a href="{{ url('/dashboard2') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Dashboard Toko</div>
                </a>
            </li>
        @endif
        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Owner')
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
                    <div data-i18n="Analytics">Toko <span class="badge bg-danger">Soon</span></div>
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
                <span class="menu-header-text">Opnema Stok</span>
            </li>
            <li class="menu-item {{ request()->is('opname-wirehouse*') ? 'active' : '' }}">
                <a href="{{ url('/opname-wirehouse') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Gudang <span class="badge bg-danger">New</span></div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Stok</span>
            </li>
            <li class="menu-item {{ request()->is('expired') ? 'active' : '' }}">
                <a href="{{ url('/expired') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trash"></i>
                    <div data-i18n="Analytics">Stok Kadaluarsa <span class="badge bg-danger">New</span></div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan</span>
            </li>
            <li class="menu-item {{ request()->is('report/price') ? 'active' : '' }}">
                <a href="{{ url('/report/price') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Harga Produk</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('report/income') ? 'active' : '' }}">
                <a href="{{ url('/report/income') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Pendapatan</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('report/stok-wirehouse') ? 'active' : '' }}">
                <a href="{{ url('/report/stok-wirehouse') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Riwayat Stok</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('report/stok-product-wirehouse') ? 'active' : '' }}">
                <a href="{{ url('/report/stok-product-wirehouse') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Stok Produk Gudang</div>
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
                    <div data-i18n="Analytics"> Transaksi Toko <span class="badge bg-danger">Soon</span></div>
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
            <li class="menu-item {{ request()->is('expired') ? 'active' : '' }}">
                <a href="{{ url('/expired') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trash"></i>
                    <div data-i18n="Analytics">Stok Kadaluarsa <span class="badge bg-danger">New</span></div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('damageds') ? 'active' : '' }}">
                <a href="{{ url('/damageds') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trash"></i>
                    <div data-i18n="Analytics">Stok Rusak</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Opnema Stok</span>
            </li>
            <li class="menu-item {{ request()->is('opname-wirehouse*') ? 'active' : '' }}">
                <a href="{{ url('/opname-wirehouse', Auth::user()->id_wirehouse) }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Opname </div>
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
            <li class="menu-item {{ request()->is('report/report-daily') ? 'active' : '' }}">
                <a href="{{ url('/report/report-daily') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div data-i18n="Analytics">Laporan Harian</div>
                </a>
            </li>
        @elseif (Auth::user()->role == 'Kasir' && Auth::user()->id_shop != null)
            <li class="menu-item cashier">
                <a href="{{ url('/transaksi-kios/cashier') }}" class="menu-link fw-bold">
                    <i class="menu-icon tf-icons bx bxs-log-in-circle"></i>
                    <div data-i18n="Analytics">Open Kasir</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>
            <li class="menu-item {{ request()->is('shop-products') ? 'active' : '' }}">
                <a href="{{ url('/shop-products') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Produk Toko</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('shop-prices') ? 'active' : '' }}">
                <a href="{{ url('/shop-prices') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Harga Produk</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Stok</span>
            </li>
            <li class="menu-item {{ request()->is('view-stock-main-wirehouse') ? 'active' : '' }}">
                <a href="{{ url('/view-stock-main-wirehouse') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Gudang Besar</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('shop-stoks') ? 'active' : '' }}">
                <a href="{{ url('/shop-stoks') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Analytics">Input Stok Toko</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi Toko</span>
            </li>
            <li class="menu-item {{ request()->is('transaksi-kios') ? 'active' : '' }}">
                <a href="{{ url('/transaksi-kios') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Analytics">Riwayat Transaksi</div>
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

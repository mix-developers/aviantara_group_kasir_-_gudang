@extends('layouts.backend.admin')

@section('content')
    <div class="text-center my-4">
        <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 30vh;"><br>
        <span class="text-warning h1">AVIANTARA</span> <span class="h4">GROUP</span>
        <hr>
    </div>
    <div class="my-3 text-center">
        <h4>Selamat datang kembali di <span class="text-primary">Sistem Informasi Managemen Gudang dan
                Kios</span>
        </h4>
        @if (Auth::user()->role == 'Gudang')
            <p class="badge bg-label-danger"><i class="bx bx-error"></i> Harap selalu cek fisik barang kadaluarsa pada gudang
                dan sistem</p>
        @endif
    </div>
    <hr>
    <div class="" id="alert"></div>
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Owner')
        <hr>
        <div class="my-4 card">
            <div class="card-body">
                <div id="chartContainer" style="height: 500px;"></div>
            </div>
        </div>
        <div class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chartContainerLunas" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chartContainerExpire" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    @endif
    <div class="row justify-content-center">
        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Owner')
            @include('admin.dashboard_component.card1', [
                'count' => $users,
                'title' => 'Pegawai',
                'subtitle' => 'Total pegawai',
                'color' => 'primary',
                'icon' => 'user',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $customers,
                'title' => 'Pelanggan',
                'subtitle' => 'Total Pelanggan',
                'color' => 'success',
                'icon' => 'user',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $shops,
                'title' => 'Toko',
                'subtitle' => 'Total Toko',
                'color' => 'warning',
                'icon' => 'store',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $wirehouses,
                'title' => 'Gudang',
                'subtitle' => 'Total Gudang',
                'color' => 'secondary',
                'icon' => 'home',
            ])
        @elseif(Auth::user()->role == 'Gudang')
            @include('admin.dashboard_component.card1', [
                'count' => $product,
                'title' => 'Produk',
                'subtitle' => 'Total Produk',
                'color' => 'warning',
                'icon' => 'layer',
            ])
        @endif

    </div>
    @if (Auth::user()->role != 'Kasir')
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4>Data Stok Barang pada gudang</h4>
            <button type="button" class="btn btn-secondary refresh-stok"><span>
                    <i class="bx bx-sync me-sm-1"> </i>
                    <span class="d-none d-sm-inline-block"></span>
                </span>
                Refresh Data</button>
        </div>

        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-primary text-white">
                    <div class="card-header" style="padding: 10px;">
                        Stok Masuk
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <div class="row">
                            <div class="col">
                                <span class="h3 text-white" id="stokInput">0</span> Stok
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-warning text-white">
                    <div class="card-header" style="padding: 10px;">
                        Stok Keluar
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <span class="h3 text-white" id="stokOut">0</span> Stok
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-danger text-white">
                    <div class="card-header" style="padding: 10px;">
                        Stok Rusak pada gudang
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <span class="h3 text-white" id="stokExpired">0</span> Stok
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-info text-white">
                    <div class="card-header" style="padding: 10px;">
                        Stok Tersedia
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <div class="row">
                            <div class="col">
                                <span class="h3 text-white" id="stokWirehouse">0</span> Total Stok
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        {{-- penjualan --}}
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4>Data Penjualan Gudang</h4>
            <button type="button" class="btn btn-secondary refresh-stok"><span>
                    <i class="bx bx-sync me-sm-1"> </i>
                    <span class="d-none d-sm-inline-block"></span>
                </span>
                Refresh Data</button>
        </div>

        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-primary text-white">
                    <div class="card-header" style="padding: 10px;">
                        Terjual
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <div class="row">
                            <div class="col">
                                <span class="h3 text-white" id="stokInput">0</span> Barang
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-warning text-white">
                    <div class="card-header" style="padding: 10px;">
                        Pembayaran Lunas
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <div class="row">
                            <div class="col">
                                <span class="h3 text-white" id="stokInput">Rp 0</span>
                            </div>
                            <div class="col">
                                <span class="h3 text-white" id="stokInput">0</span> Customer
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-danger text-white">
                    <div class="card-header" style="padding: 10px;">
                        Pembayaran Belum Lunas
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <div class="row">
                            <div class="col">
                                <span class="h3 text-white" id="stokInput">Rp 0</span>
                            </div>
                            <div class="col">
                                <span class="h3 text-white" id="stokInput">0</span> Customer
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card bg-info text-white">
                    <div class="card-header" style="padding: 10px;">
                        Pendapatan
                    </div>
                    <div class="card-body" style="padding: 10px;">
                        <div class="row">
                            <div class="col">
                                <span class="h3 text-white" id="stokWirehouse">Rp 0</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4>Data Stok Barang pada kios</h4>
            <button type="button" class="btn btn-secondary refresh-stok-kios"><span>
                    <i class="bx bx-sync me-sm-1"> </i>
                    <span class="d-none d-sm-inline-block"></span>
                </span>
                Refresh Stok</button>
        </div>
    @endif
@endsection
@include('admin.dashboard_component.script')

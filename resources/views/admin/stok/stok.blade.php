@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
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
                        <div class="col">
                            Rp <span class="h5 text-white" id="priceStokInput">0</span>
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
                    Stok Kadaluarsa pada gudang
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
                        <div class="col">
                            <span class="h3 text-white" id="stokNotExpired">0</span> Aman
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }} </h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class=" btn-group " role="group">
                            <button class="btn btn-secondary refresh btn-default" type="button">
                                <span>
                                    <i class="bx bx-sync me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block"></span>
                                </span>
                            </button>
                            <button class="btn btn-secondary create-new btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#create">
                                <span>
                                    <i class="bx bx-plus me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Tambah Data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
                <div style="margin-left:24px; margin-right: 24px;">
                    <strong>Filter Data</strong>
                    <div class="d-flex justify-content-center align-items-center row gap-3 gap-md-0">

                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Tanggal input</span>
                                <input type="date" class="form-control" id="fromDate"
                                    value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                <span class="input-group-text"> - </span>
                                <input type="date" class="form-control" id="toDate" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4  col-12 mb-3">
                            <select id="selectExpired" class="form-select text-capitalize">
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-12 mb-3">
                            <select id="selectTypeStok" class="form-select text-capitalize">
                            </select>
                        </div>
                        @if (Auth::user()->role != 'Gudang')
                            <div class="col-lg-2 col-md-4 col-12 mb-3">
                                <select id="selectUser" class="form-select text-capitalize">
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="card-datatable table-responsive">
                    <table id="datatable-stok" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width:10px;"><i class="bx bx-info-circle"></i>
                                </th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Kadaluarsa</th>
                                @if (Auth::user()->role != 'Gudang')
                                    <th>Pegawai</th>
                                @endif
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th><i class="bx bx-info-circle"></i></th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Kadaluarsa</th>
                                @if (Auth::user()->role != 'Gudang')
                                    <th>Pegawai</th>
                                @endif
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.stok.components.stok.modal_stok')
@endsection
@include('admin.stok.script_stok')

@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="" id="alert"></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }}</h5>
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
                                data-bs-target="#stok_form">
                                <span>
                                    <i class="bx bx-plus me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Tambah Transaksi</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-stok-kios" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Transaksi</th>
                                <th>Nama Kios</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Tgl Kadaluarsa</th>
                                <th>Pengguna</th>
                                <th>Action</th>
                            </tr>
                        </thead>                        
                    </table>
                </div>

            </div>
        </div>
    </div>
    @include('admin.transaksi.modal')
@endsection
@include('admin.transaksi.script')

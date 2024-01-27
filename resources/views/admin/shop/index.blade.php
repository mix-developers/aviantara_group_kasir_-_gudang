@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="" id="alert"></div>
    <div class="row">

        @foreach ($shops as $item)
            <div class="col-lg-3 col-md-4 col-6 mb-4">
                <div class="card border border-warning">
                    <div class="card-header">
                        <strong>{{ $item->name }}</strong>
                    </div>
                    <div class="card-body">
                        <span class=" text-danger h2" id="totalProduk">
                            0
                        </span>
                        Produk
                    </div>
                </div>
            </div>
        @endforeach
    </div>
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
                                data-bs-target="#create">
                                <span>
                                    <i class="bx bx-plus me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Tambah Data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-shop" class="table table-hover display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat Toko</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat Toko</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.shop.components.modal')
@endsection
@include('admin.shop.script')

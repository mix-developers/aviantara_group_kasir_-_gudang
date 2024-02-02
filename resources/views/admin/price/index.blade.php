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
                                Refresh
                            </button>

                        </div>
                    </div>
                </div>
                <hr>
                <div style="margin-left:24px; margin-right: 24px;">
                    <strong>Filter Data</strong>
                    <div class="d-flex justify-content-center align-items-center row gap-3 gap-md-0">
                        <div class="col-md-3 col-12">
                            <select id="selectWirehouse" class="form-select text-capitalize">

                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-datatable table-responsive">
                    <table id="datatable-price" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Gudang</th>
                                <th>Isi Produk</th>
                                <th>Harga Grosir</th>
                                <th>Keuntungan</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Gudang</th>
                                <th>Isi Produk</th>
                                <th>Harga Grosir</th>
                                <th>Keuntungan</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.price.components.modal')
@endsection
@include('admin.price.script')

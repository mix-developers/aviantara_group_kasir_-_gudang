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
                                    <span class="d-none d-sm-inline-block">Tambah Data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="px-4">
                    <label for="filterType" class="form-label fw-semibold">
                      Filter:
                    </label>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <select name="type" id="filterType" class="form-select">
                            <option value="Masuk" selected>Masuk</option>
                            <option value="Keluar">Keluar</option>
                          </select>
                      <button type="button" class="btn-filter btn btn-primary d-flex align-items-center gap-2">
                        <i class="bx bx-filter"></i> Filter
                      </button>
                    </div>
                  </div>
                <hr>
                <div class="card-datatable table-responsive">
                    <table id="datatable-stok-kios" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th>Type</th>
                                <th>Stok</th>
                                <th>Harga Modal</th>
                                <th>Tgl Kadaluarsa</th>
                                @if (Auth::user()->role == 'Owner' || Auth::user()->role == 'Admin')
                                    <th>Oleh</th>
                                @endif
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.shop_stock.modal')
@endsection
@include('admin.shop_stock.script')

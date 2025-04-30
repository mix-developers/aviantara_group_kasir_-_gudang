@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="" id="alert"></div>
    <div class="row justify-content-center">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <button id="toggleButton" class="btn btn-primary btn-sm mb-3">Tampilkan / Sembunyikan Grafik</button>
                    <div class="" id="expiredData">
                        <div>
                            <h5>Jumlah Stok Kadaluarsa per Bulan (1 Tahun)</h5>
                            <canvas id="expiredStockChart" height="50px"></canvas>
                        </div>
                        @if (Auth::user()->role == 'Owner' || Auth::user()->role == 'Admin')
                            <hr>
                            <div>
                                <h5>Jumlah Kerugian Kadaluarsa per Bulan (1 tahun)</h5>
                                <canvas id="expiredLossChart" height="50px"></canvas>
                                <p id="totalLoss" style="font-weight: bold; margin-top: 10px;"></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
                                    <span class="d-none d-sm-inline-block">Refresh Data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-datatable table-responsive">
                    <table id="datatable-expired" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Kadaluarsa</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Kadaluarsa</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.expired.components.modal')
@endsection
@include('admin.expired.script')
@push('js')
    <script>
        document.getElementById("toggleButton").addEventListener("click", function() {
            var expiredData = document.getElementById("expiredData");
            if (expiredData.style.display === "none") {
                expiredData.style.display = "block";
            } else {
                expiredData.style.display = "none";
            }
        });

        // Sembunyikan secara default
        document.getElementById("expiredData").style.display = "none";
    </script>
@endpush

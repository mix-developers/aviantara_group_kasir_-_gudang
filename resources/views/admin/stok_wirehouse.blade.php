@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="" id="alert"></div>
    <div class="row" id="wirehouseCard">
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
                                    <span class="d-none d-sm-inline-block">Refresh Data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-product" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th>Harga Grosir</th>
                                <th>Gudang</th>
                                <th>Stok</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th>Harga Grosir</th>
                                <th>Gudang</th>
                                <th>Stok</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var dataTable;
        $(function() {
            dataTable = $('#datatable-product').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('datatable-stock-main-wirehouse') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'barcode',
                        name: 'barcode'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'wirehouse.name',
                        name: 'wirehouse.name'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    }
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-product').DataTable().ajax.reload();
            });

            function getWirehouseCard() {
                $.ajax({
                    url: '/wirehouses/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#wirehouseCard').empty();

                        $.each(data, function(index, wirehouse) {
                            $.getJSON("wirehouse-total-product/" + wirehouse.id, function(
                                respons) {
                                var totalProduk = respons.total;

                                var textClasss = (totalProduk === 0) ? 'text-danger' :
                                    'text-primary';

                                $('#wirehouseCard').append(
                                    '<div class="col-md-2 col-6 mb-4"><div class="card border border-primary"><div class="card-header" style="padding:10px;">' +
                                    wirehouse.name +
                                    '</div><div class="card-body" style="padding:10px;"> <span class = " ' +
                                    textClasss +
                                    ' h2"  > ' + totalProduk +
                                    ' </span>Produk </div></div> </div>'
                                );
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan: ' + error);
                    }
                });
            }
            getWirehouseCard();
        });
    </script>
@endpush

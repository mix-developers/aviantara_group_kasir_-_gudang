@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row justify-content-center">

        <div class="col-12">
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
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-detail-price" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>produk</th>
                                <th>tanggal</th>
                                <th>Harga Grosir</th>
                                <th>Pegawai</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>produk</th>
                                <th>tanggal</th>
                                <th>Harga Grosir</th>
                                <th>Pegawai</th>
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
        $(function() {
            $('#datatable-detail-price').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('price-detail-datatable', $product->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'grosir',
                        name: 'grosir'
                    },

                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                ],
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ]
            });

            $('.refresh').click(function() {
                $('#datatable-detail-price').DataTable().ajax.reload();
            });

        });
    </script>
@endpush

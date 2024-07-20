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
                                    <span class="d-none d-sm-inline-block">Refresh </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-detail-customer" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>No. Invoice</th>
                                <th>Harga Total</th>
                                <th>Pengantaran</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>No. Invoice</th>
                                <th> Harga Total</th>
                                <th>Pengantaran</th>
                                <th>Keterangan</th>
                                <th>Status</th>
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
            $('#datatable-detail-customer').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('customers-datatable-detail', $customer->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'no_invoice',
                        name: 'no_invoice'
                    },
                    {
                        data: 'tagihan',
                        name: 'tagihan'
                    },
                    {
                        data: 'delivery_text',
                        name: 'delivery_text'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'payment',
                        name: 'payment'
                    },

                ]
            });
            $('.refresh').click(function() {
                $('#datatable-detail-customer').DataTable().ajax.reload();
            });


        });
    </script>
@endpush

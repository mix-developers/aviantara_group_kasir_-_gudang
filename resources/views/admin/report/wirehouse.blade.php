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
                                    <span class="d-none d-sm-inline-block">Refresh</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
                <div style="margin-left:24px; margin-right: 24px;">
                    <strong>Filter Data</strong>
                    <div class="d-flex justify-content-center align-items-center row gap-3 gap-md-0">
                        <div class="col-md-4 col-12">
                            <div class="input-group">
                                <span class="input-group-text">Tanggal</span>
                                <input type="date" class="form-control" id="fromDate"
                                    value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                <span class="input-group-text"> - </span>
                                <input type="date" class="form-control" id="toDate" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <select id="selectWirehouse" class="form-select text-capitalize">
                                <option value="-">Semua Gudang</option>
                                @foreach (App\Models\Wirehouse::all() as $wirehouse)
                                    <option value="{{ $wirehouse->id }}">{{ $wirehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <button type="button" id="filterBtn" class="btn btn-primary"><i class="bx bx-filter"></i>
                                Filter</button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-datatable table-responsive">
                    <table id="datatable-payment" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>No. Invoice</th>
                                <th>Pelanggan</th>
                                <th>Gudang</th>
                                <th>Tagihan</th>
                                <th>Terbayar</th>
                                <th>Sisa</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>No. Invoice</th>
                                <th>Pelanggan</th>
                                <th>Gudang</th>
                                <th>Tagihan</th>
                                <th>Terbayar</th>
                                <th>Sisa</th>
                                <th>status</th>
                                <th>Action</th>
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
            var table = $('#datatable-payment').DataTable({
                processing: true,
                serverSide: false,
                responsive: false,
                ajax: '{{ url('order-wirehouses-datatable') }}',
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
                        data: 'customer.name',
                        name: 'customer.name'
                    },
                    {
                        data: 'wirehouse',
                        name: 'wirehouse'
                    },
                    {
                        data: 'total_fee',
                        name: 'total_fee'
                    },
                    {
                        data: 'terbayar',
                        name: 'terbayar'
                    },
                    {
                        data: 'sisa',
                        name: 'sisa'
                    },

                    {
                        data: 'payment',
                        name: 'payment'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],

                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="bx bxs-file-pdf"></i> PDF',
                        className: 'btn-danger mx-3',
                        orientation: 'landscape',
                        title: '{{ $title . date('d-m-y H:i:s') }}',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 8;
                            doc.styles.tableHeader.fontSize = 8;
                            doc.styles.tableHeader.fillColor = '#2a6908';
                        },
                        header: true,
                        action: function(e, dt, button, config) {
                            var fromDate = $('#fromDate').val();
                            var toDate = $('#toDate').val();
                            var selectWirehouse = $('#selectWirehouse').val();

                            var url = '/report/pdf-wirehouses?wirehouse=' + selectWirehouse +
                                '&from-date=' + fromDate + '&to-date=' + toDate;

                            window.open(url, '_blank');
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bx bxs-file-export"></i> Excel',
                        title: '{{ $title . date('d-m-y H:i:s') }}',
                        className: 'btn-success',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-payment').DataTable().ajax.reload();
            });
            $('#filterBtn').click(function() {
                var selectWirehouse = $('#selectWirehouse').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();

                var newUrl = '{{ url('order-wirehouses-datatable') }}?wirehouse=' + selectWirehouse +
                    '&from-date=' + fromDate + '&to-date=' + toDate;
                table.ajax.url(newUrl).load();
            });

            function getAlert(alertValue) {
                $('#alert').append(
                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }
        });
    </script>
    <!-- JS DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush

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
                <div class="card-datatable ">
                    <table id="datatable-price" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Gudang</th>
                                <th>Isi Produk</th>
                                <th>Harga Modal</th>
                                <th>Harga Grosir</th>
                                <th>Keuntungan</th>
                                <th>%</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Gudang</th>
                                <th>Isi Produk</th>
                                <th>Harga Modal</th>
                                <th>Harga Grosir</th>
                                <th>Keuntungan</th>
                                <th>%</th>
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
            dataTable = $('#datatable-price').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('prices-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'wirehouse',
                        name: 'wirehouse',
                        searchable: true
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'price_origin',
                        name: 'price_origin'
                    },
                    {
                        data: 'price_grosir',
                        name: 'price_grosir'
                    },
                    {
                        data: 'price_fee',
                        name: 'price_fee'
                    },
                    {
                        data: 'percentese_fee_text',
                        name: 'percentese_fee_text'
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
                            var selectWirehouse = $('#selectWirehouse').val();

                            var url = '/report/pdf-price?wirehouse=' + selectWirehouse;

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
                $('#datatable-price').DataTable().ajax.reload();
            });

            $('#selectWirehouse').on('change', function() {
                applyFilters();
            });

            function applyFilters() {
                var wirehouseFilter = $('#selectWirehouse').val();

                var newUrl = '{{ url('prices-datatable') }}?&wirehouse=' + wirehouseFilter;
                dataTable.ajax.url(newUrl).load();
            }

            function getWirehouseOptions(unitValue) {
                $.ajax({
                    url: '/wirehouses/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#formProductIdWirehouse').empty();
                        $('#formProductIdWirehouseCreate').empty();

                        $('#selectWirehouse').empty();
                        $('#selectWirehouse').append(
                            '<option value="-" >Pilih Gudang</option>');
                        $.each(data, function(index, wirehouse) {
                            $('#selectWirehouse').append('<option value="' +
                                wirehouse.id +
                                '" >' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');
                        });

                        $.each(data, function(index, wirehouse) {
                            $('#formProductIdWirehouseCreate').append(
                                '<option value="' +
                                wirehouse.id +
                                '" >' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');

                        });

                        $.each(data, function(index, wirehouse) {
                            var selected = (wirehouse.id === unitValue) ? 'selected' :
                                '';
                            $('#formProductIdWirehouse').append('<option value="' +
                                wirehouse
                                .id +
                                '" ' +
                                selected + '>' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan: ' + error);
                    }
                });
            }

            getWirehouseOptions();
        });
    </script>
    <!-- JS DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush

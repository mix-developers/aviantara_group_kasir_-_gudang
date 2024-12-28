@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row mb-4 justify-content-center">
        <div class="col-md-3 col-6 mb-2">
            <div class="card bg-primary text-white">
                <div class="card-header" style="padding: 10px;">
                    Stok Masuk
                </div>
                <div class="card-body" style="padding: 10px;">
                    <div class="row">
                        <div class="col">
                            <span class="h3 text-white" id="stokInput">0</span> Stok
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card bg-warning text-white">
                <div class="card-header" style="padding: 10px;">
                    Stok Keluar
                </div>
                <div class="card-body" style="padding: 10px;">
                    <span class="h3 text-white" id="stokOut">0</span> Stok
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card bg-danger text-white">
                <div class="card-header" style="padding: 10px;">
                    Stok Rusak pada gudang
                </div>
                <div class="card-body" style="padding: 10px;">
                    <span class="h3 text-white" id="stokExpired">0</span> Stok
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card bg-info text-white">
                <div class="card-header" style="padding: 10px;">
                    Stok Tersedia
                </div>
                <div class="card-body" style="padding: 10px;">
                    <div class="row">
                        <div class="col">
                            <span class="h3 text-white" id="stokWirehouse">0</span> Total Stok
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }} </h5>
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
                <div style="margin-left:24px; margin-right: 24px;">
                    <strong>Filter Data</strong>
                    <div class="d-flex justify-content-center align-items-center row gap-3 gap-md-0">

                        <div class="col-lg-6 col-md-8 col-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Tanggal input</span>
                                <input type="date" class="form-control" id="fromDate"
                                    value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                <span class="input-group-text"> - </span>
                                <input type="date" class="form-control" id="toDate" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mb-3">
                            <select id="selectWirehouse" class="form-select text-capitalize">
                                <option value="-">Semua Gudang</option>
                                @foreach (App\Models\Wirehouse::all() as $wirehouse)
                                    <option value="{{ $wirehouse->id }}">{{ $wirehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <hr>
                <div class="card-datatable">
                    <table id="datatable-stok" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Gudang</th>
                                <th>Stok</th>
                                <th>Stok Retail</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Gudang</th>
                                <th>Stok</th>
                                <th>Stok Retail</th>
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
            var dataColumn = [{
                    data: 'id',
                    name: 'id'
                },

                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'wirehouse',
                    name: 'wirehouse'
                },
                {
                    data: 'stok',
                    name: 'stok'
                },
                {
                    data: 'stok_retail',
                    name: 'stok_retail'
                },

            ];

            dataTable = $('#datatable-stok').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('report/stok-product-wirehouse-datatable') }}',
                columns: dataColumn,
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
                            var wirehouseFilter = $('#selectWirehouse').val();
                            var fromDate = $('#fromDate').val();
                            var toDate = $('#toDate').val();


                            var url = '/report/pdf-stok-product-wirehouse?wirehouse=' +
                                wirehouseFilter +
                                '&from-date=' +
                                fromDate + '&to-date=' + toDate;

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
                $('#datatable-stok').DataTable().ajax.reload();
                getStok().ajax.reload;
            });

            $('#selectWirehouse,#fromDate, #toDate').on('change', function() {
                applyFilters();
            });

            function applyFilters() {
                var wirehouseFilter = $('#selectWirehouse').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();

                var newUrl = '{{ url('report/stok-product-wirehouse-datatable') }}?wirehouse=' + wirehouseFilter +
                    '&from-date=' +
                    fromDate + '&to-date=' + toDate;

                dataTable.ajax.url(newUrl).load();

            }



            function getTypeStokOptions() {
                var staticData = ['Stok Masuk', 'Stok Keluar'];

                $('#selectTypeStok').empty();
                $('#selectTypeStok').append(
                    '<option value="-" >Pilih Jenis Stok</option>');

                $.each(staticData, function(index, type) {
                    $('#selectTypeStok').append('<option value="' +
                        type +
                        '" >' +
                        type +
                        '</option>');
                });;
            }

            function getExpiredOptions() {
                var staticData = ['Telah Kadaluarsa', 'Akan Kadaluarsa', 'Belum Kadaluarsa'];

                $('#selectExpired').empty();
                $('#selectExpired').append(
                    '<option value="-" >Pilih Status Kadaluarsa</option>');

                $.each(staticData, function(index, status) {
                    $('#selectExpired').append('<option value="' +
                        status +
                        '" >' +
                        status +
                        '</option>');
                });;
            }

            function getUserOptions() {
                $.ajax({
                    url: '/users/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#selectUser').empty();
                        $('#selectUser').append(
                            '<option value="-" >Pilih Pegawai</option>');

                        $.each(data, function(index, user) {
                            if (user.role === 'Gudang') {
                                $('#selectUser').append('<option value="' +
                                    user.id +
                                    '" >' +
                                    user.name + ' - ' + user.role + '</option>');
                            }
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan select user: ' + error);
                    }
                });
            }

            function getWirehouseOptions(unitValue) {
                $.ajax({
                    url: '/wirehouses/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#formProductIdWirehouse').empty();
                        $('#formProductIdWirehouseCreate').empty();

                        $.each(data, function(index, wirehouse) {
                            $('#formProductIdWirehouseCreate').append('<option value="' +
                                wirehouse.id +
                                '" >' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');

                        });
                        $.each(data, function(index, wirehouse) {
                            var selected = (wirehouse.id === unitValue) ? 'selected' : '';
                            $('#formProductIdWirehouse').append('<option value="' + wirehouse
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


            function getStok() {
                $.ajax({
                    type: 'GET',
                    url: '/get-stok-card',
                    success: function(response) {

                        $('#stokInput').text(response.stok_input);
                        $('#stokOut').text(response.stok_out);
                        $('#stokExpired').text(response.stok_expired);
                        $('#stokNotExpired').text(response.stok_not_expired);
                        $('#stokWirehouse').text(response.stok_wirehouse);
                        $('#priceStokInput').text(formatNumberWithDot(response.price_stok_input));
                    }
                });
            };

            function formatNumberWithDot(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            getStok();
            getUserOptions();
            getTypeStokOptions();
            getExpiredOptions();
        });
    </script>
    <!-- JS DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush

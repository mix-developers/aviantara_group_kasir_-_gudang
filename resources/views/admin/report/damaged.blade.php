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
                                    <span class="d-none d-sm-inline-block"> Refresh Data</span>
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
                        <div class="col-md-2 col-12">
                            <select id="selectWirehouse" class="form-select text-capitalize">
                                <option value="-">Semua Gudang</option>
                                @foreach (App\Models\Wirehouse::all() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <select id="selectUser" class="form-select text-capitalize">
                                <option value="-">Semua Pegawai</option>
                                @foreach (App\Models\User::where('role', 'Gudang')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->wirehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <select id="selectType" class="form-select text-capitalize">
                                <option value="-">Semua Jenis</option>
                                <option value="Kadaluarsa">Kadaluarsa</option>
                                <option value="Rusak">Rusak</option>
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
                    <table id="datatable-damageds" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Gudang</th>
                                <th>Produk</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Sub Jumlah </th>
                                <th>Kadaluarsa</th>
                                <th>Pegawai</th>
                                <th>Lihat</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Gudang</th>
                                <th>Produk</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Sub Jumlah </th>
                                <th>Kadaluarsa</th>
                                <th>Pegawai</th>
                                <th>Lihat</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="show" tabindex="-1" aria-labelledby="productSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding-left:5px; padding-right:5px;">
                    <!-- Placeholder for images -->
                    <img id="photo1" src="" alt="Photo 1" style="width: 100%; height: auto;" />
                    <img id="photo2" src=""
                        alt="Photo 2"style="width: 100%; height: auto; margin-top: 10px;  display:none;" />
                    <div class="mt-2">
                        <h4>keterangan :</h4>
                        <p id="description_show"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            var table = $('#datatable-damageds').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('damageds-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'wirehouse.name',
                        name: 'wirehouse.name'
                    },
                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'quantity_unit',
                        name: 'quantity_unit'
                    },
                    {
                        data: 'quantity_sub_unit',
                        name: 'quantity_sub_unit'
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
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
                            var selectUser = $('#selectUser').val();
                            var selectWirehouse = $('#selectWirehouse').val();
                            var selectType = $('#selectType').val();
                            var fromDate = $('#fromDate').val();
                            var toDate = $('#toDate').val();

                            var url = '{{ url('report/pdf-damaged') }}?user=' + selectUser +
                                '&wirehouse=' + selectWirehouse + '&type=' + selectType +
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
                $('#datatable-damageds').DataTable().ajax.reload();
            });
            window.showDamaged = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/damageds/edit/' + id,
                    success: function(response) {

                        $('#photo1').attr('src', response.photo_url);
                        if (response.photo2 != null) {
                            $('#photo2').attr('src', response.photo2_url).show();
                        } else {
                            $('#photo2').hide();
                        }
                        $('#description_show').text(response.description);

                        // Show the modal
                        $('#show').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#filterBtn').click(function() {
                var selectUser = $('#selectUser').val();
                var selectWirehouse = $('#selectWirehouse').val();
                var selectType = $('#selectType').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();

                var newUrl = '{{ url('damageds-datatable') }}?user=' + selectUser +
                    '&wirehouse=' + selectWirehouse + '&type=' + selectType + '&from-date=' + fromDate +
                    '&to-date=' + toDate;

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

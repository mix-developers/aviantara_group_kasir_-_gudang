@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
            <div class="card mb-3">
                <div class="card-header">
                    Informasi Toko
                </div>
                <table class="table table-hover">
                    <tr>
                        <td class="fw-bold">Nama Toko</td>
                        <td>:</td>
                        <td class="text-primary">{{ $shops->name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Alamat Toko</td>
                        <td>:</td>
                        <td>{{ $shops->address }}</td>
                    </tr>
                </table>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    Pegawai Toko
                </div>
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staff as $itemStaff)
                            <tr>
                                <td>{{ $itemStaff->id }}</td>
                                <td><strong>{{ $itemStaff->name }}</strong><br><small
                                        class="text-mutted">{{ $itemStaff->email }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-8">
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
                    <table id="datatable-stok-kios" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Barcode</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Tgl Kadaluarsa</th>
                                <th>Pengguna</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Kode Barcode</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Tgl Kadaluarsa</th>
                                <th>Pengguna</th>
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
            $('#datatable-stok-kios').DataTable({
                processing: true,
                // serverSide: false,
                responsive: true,
                ajax: {
                    url: '{{ url('/kios_stok/getShop', $shops->id) }}',
                    type: 'GET',
                    // dataType: 'json',
                    dataSrc: 'data' // Nama properti yang berisi data dalam respons JSON
                },
                columns: [

                    {
                        data: 'id',
                        name: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Mengembalikan nomor urut baris
                        }

                    },
                    {
                        data: 'product.barcode',
                        name: 'product.barcode',

                    },


                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data, type, row) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date',
                        render: function(data, type, row) {
                            var currentDate = moment().startOf('day');
                            // var currentDate = moment('2026-09-08').startOf('day');
                            if (currentDate.isBefore(data)) {
                                return '<strong class="h6 text-success">' + moment(data).format(
                                    'DD MMMM YYYY') + '</strong>';
                            } else {
                                return '<strong class="h6 text-danger">' + moment(data).format(
                                    'DD MMMM YYYY') + ' (telah kadaluarsa)</strong>';
                            }
                            // return moment(data).locale('id').format('DD MMMM YYYY');
                        }
                    },

                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                ],

                error: function(xhr, status, error) {
                    // Tangani kesalahan saat mengambil data
                    console.error(error);
                },


            });


            $('.refresh').click(function() {
                $('#datatable-stok-kios').DataTable().ajax.reload();
            });



            function getAlert(alertValue) {
                $('#alert').append(
                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }


        });

        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + ribuan;
        }
    </script>
@endpush

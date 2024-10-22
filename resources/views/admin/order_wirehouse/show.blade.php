@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="my-3">
        {{-- <a href="" class="btn btn-danger"><i class="bx bxs-file-pdf"></i> Export PDF</a> --}}
        <a href="" class="btn btn-primary"
            onclick="window.open('{{ url('/payments/print-delivery', $order->id) }}', 'Print Invoice', 'width=800,height=600')">
            <i class="bx bxs-truck"></i> Invoice Pengantaran</a>
        <a href="" class="btn btn-success"
            onclick="window.open('{{ url('/order_wirehouses/print-invoice', $order->id) }}', 'Print Invoice', 'width=800,height=600')">
            <i class="bx bx-printer"></i> Invoice Pembelian</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Nama Pelanggan</td>
                            <td>:</td>
                            <td>{{ $order->customer->name }}</td>
                        </tr>
                        <tr>
                            <td>Nomor HP</td>
                            <td>:</td>
                            <td>{{ $order->customer->phone }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Rumah</td>
                            <td>:</td>
                            <td>{{ $order->customer->address_home }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Usaha</td>
                            <td>:</td>
                            <td>{{ $order->customer->address_company }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Invoice</td>
                            <td>:</td>
                            <td>{{ $order->no_invoice }}</td>
                        </tr>
                        <tr>
                            <td>Harga Pesanan</td>
                            <td>:</td>
                            <td>Rp {{ number_format($order->total_fee - $order->additional_fee) }}</td>
                        </tr>
                        <tr>
                            <td>Harga Tambahan</td>
                            <td>:</td>
                            <td>Rp {{ number_format($order->additional_fee) }}</td>
                        </tr>
                        <tr>
                            <td>Diskon</td>
                            <td>:</td>
                            <td>{{ $order->discount }} %</td>
                        </tr>
                        <tr>
                            <td>Pengantaran</td>
                            <td>:</td>
                            <td>{{ $order->delivery == 1 ? 'Diantar' : 'Ambil ditempat' }}</td>
                        </tr>
                        <tr>
                            <td>Total Tagihan</td>
                            <td>:</td>
                            <td>Rp {{ number_format($order->total_fee) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">Daftar Produk Invoice : {{ $order->no_invoice }}</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class=" btn-group " role="group">
                            <button class="btn btn-secondary refresh-order btn-default" type="button">
                                <span>
                                    <i class="bx bx-sync me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Refresh</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-order-item" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">Data Pembayaran Invoice : {{ $order->no_invoice }}</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class=" btn-group " role="group">
                            <button class="btn btn-secondary refresh btn-default" type="button">
                                <span>
                                    <i class="bx bx-sync me-sm-1"> </i>
                                    <span
                                        class="d-none d-sm-inline-block">{{ Auth::user()->role == 'Gudang' ? '' : 'Refresh' }}</span>
                                </span>
                            </button>
                            @if (Auth::user()->role == 'Gudang')
                                <button class="btn btn-secondary create-new btn-primary" type="button"
                                    data-bs-toggle="modal" data-bs-target="#create">
                                    <span>
                                        <i class="bx bx-plus me-sm-1"> </i>
                                        <span class="d-none d-sm-inline-block">Tambah Data</span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-detail-payment" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Metode Pembayaran</th>
                                <th>Dibayarkan</th>
                                <th>Penerima</th>
                                @if (Auth::user()->role == 'Gudang')
                                    <th><i class="bx bx-trash"></i></th>
                                @endif
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Metode Pembayaran</th>
                                <th>Dibayarkan</th>
                                <th>Penerima</th>
                                @if (Auth::user()->role == 'Gudang')
                                    <th><i class="bx bx-trash"></i></th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @include('admin.payment.components.modal')
@endsection
@push('js')
    <script>
        $(function() {
            $('#datatable-detail-payment').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('payment-detail-datatable', $order->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'payment_method.method',
                        name: 'payment_method.method'
                    },
                    {
                        data: 'paid',
                        name: 'paid'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    @if (Auth::user()->role == 'Gudang')
                        {
                            data: 'trash',
                            name: 'trash'
                        },
                    @endif
                ]
            });
            $('#datatable-order-item').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('order-wirehouses-item-datatable', $order->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal'
                    },
                ]
            });

            $('.refresh-order').click(function() {
                $('#datatable-order-item').DataTable().ajax.reload();
            });
            $('.refresh').click(function() {
                $('#datatable-detail-payment').DataTable().ajax.reload();
            });
            $('.create-new').click(function() {
                $('#create').modal('show');
                $('#idOrderWirehouse').val('{{ $order->id }}');
                getPaymentMethodOptions();

            });

            function getPaymentMethodOptions() {
                $.ajax({
                    url: '/paymentMethod/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#formProductIdWirehouseCreate').empty();
                        $('#selectPaymentMethod').empty();
                        $.each(data, function(index, method) {
                            $('#selectPaymentMethod').append(
                                ' <div class="form-check form-check-inline mt-3"><input  class="form-check-input" type="radio" name="id_payment_method" value="' +
                                method.id +
                                '" >' +
                                '</input><label class="form-check-label">' +
                                method.method +
                                '</label></div>');

                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan: ' + error);
                    }
                });
            }
            $('#createPaymentBtn').click(function() {
                var formData = $('#createPaymentForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/payments/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#datatable-detail-payment').DataTable().ajax.reload();
                        $('#paid').val('');
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });

        });
    </script>
@endpush

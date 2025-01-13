@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="my-3">
        {{-- <a href="" class="btn btn-danger"><i class="bx bxs-file-pdf"></i> Export PDF</a> --}}
        @if ($order->delivery == 1)
            <a href="" class="btn btn-primary mb-3"
                onclick="window.open('{{ url('/payments/print-delivery', $order->id) }}', 'Print Invoice', 'width=800,height=600')">
                <i class="bx bxs-truck"></i> Invoice Pengantaran</a>
        @endif
        <a href="" class="btn btn-success mb-3"
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
                        @if ($order->due_date != null)
                            <tr>
                                <td>Jatuh Tempo</td>
                                <td>:</td>
                                <td class="text-danger">{{ $order->due_date }}</td>
                            </tr>
                        @endif
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
                        <tr>
                            <td>Sisa Pembayaran</td>
                            <td>:</td>
                            <td class="text-danger fw-bold">Rp
                                {{ number_format($order->total_fee - App\Models\OrderWirehousePayment::where('id_order_wirehouse', $order->id)->sum('paid')) }}
                            </td>
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
                                <th style="width: 10px;"></th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th style="width: 10px;"></th>
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
    <div class="modal fade" id="discountProduct" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content bg-primary text-white " style=" border: 2px solid white;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body text-center">
                    <form id="discountForm">
                        <input type="hidden" id="discountProductId" name="id">
                        <input type="hidden" id="hargaSemula" name="harga_semula">
                        <input type="hidden" id="hargaSemulaItem" name="harga_semula_item">
                        <h3 class="mb-3 fw-bold text-white">Discount : <span id="discountNameProduct"></span>
                        </h3>
                        <hr>
                        <div class="mb-3">
                            <label>Pilih Metode Diskon</label>
                            <select class="form-select" id="selectMethodDiscount">
                                <option value="persen">Persen (%)</option>
                                <option value="rupiah">Rupiah (Rp)</option>
                            </select>
                        </div>
                        {{-- ini persentase --}}
                        <div class="mb-3" id="discountPersen">
                            <div class="input-group">
                                <input type="number" class="form-control" name="discount_persen" id="discountProductPersen"
                                    value="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        {{-- ini rupiah --}}
                        <div class="mb-3" id="discountRupiah" style="display: none;">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control discountProductRupiah" name="discount_rupiah"
                                    value="0" id="discountProductRupiah">
                            </div>
                        </div>
                    </form>
                    <hr>
                    <button type="button" class="btn btn-danger discountBatal">Batalkan</button>
                    <button type="button" class="btn btn-light" id="applyDiscountButton">
                        <div class="spinner-border spinner-border-sm text-prmary" role="status"
                            id="applyDiscountButtonSpinner" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
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
                        data: 'subtotal_text',
                        name: 'subtotal_text'
                    },
                    {
                        data: null,
                        name: 'button',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                            <button type="button" class="btn text-primary btn-sm " onclick="diskonItem(${row.id}, '${row.product.name}', '${row.subtotal}')">
                                                <i class="bx bx-dollar-circle"></i> Diskon
                                            </button>
                                        `;
                        }
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
            window.diskonItem = function(id, productName, subtotal) {
                $('#discountProduct').modal('show');
                $('#selectMethodDiscount').on('change', function() {
                    const selectedMethod = $(this).val();

                    if (selectedMethod === 'persen') {
                        $('#discountPersen').show();
                        $('#discountRupiah').hide();
                        $('#discountProductRupiah').val(0);
                    } else if (selectedMethod ===
                        'rupiah') {
                        $('#discountPersen').hide();
                        $('#discountRupiah').show();
                        $('#discountProductPersen').val(0);
                    }
                    $currentRow = $(this).closest('tr');
                });
                $('#discountProductId').val(id);
                $('#hargaSemulaItem').val(subtotal);
                $('#discountNameProduct').text(productName);

                $('.discountBatal').click(function() {
                    $('#discountProduct').modal('hide');
                    // alert('batal');

                });
                $('#applyDiscountButton').click(function() {
                    $('#applyDiscountButtonSpinner').show();
                    $('#applyDiscountButton').prop('disabled', true);
                    // alert('aply');
                    var formData = $('#discountForm').serialize();
                    $.ajax({
                        type: 'POST',
                        url: '/discount-order-items/store',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $(
                                    'meta[name="csrf-token"]')
                                .attr('content')
                        },
                        success: function(response) {
                            $('#applyDiscountButtonSpinner')
                                .hide();
                            $('#applyDiscountButton')
                                .prop(
                                    'disabled', false);
                            $('#datatable-order-item')
                                .DataTable()
                                .ajax.reload();
                            $('#discountProduct')
                                .modal(
                                    'hide');
                            total_tagihan = response
                                .new_total;
                            $('#payment-tagihan')
                                .text(
                                    total_tagihan);

                        },
                        error: function(xhr, status, error) {
                            $('#applyDiscountButtonSpinner')
                                .hide();
                            $('#applyDiscountButton').prop(
                                'disabled', false);
                            console.error(
                                'Terjadi kesalahan: ' +
                                error);
                            $('#discountProduct').modal(
                                'hide');

                        }
                    });

                });

            };

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
                $('#createPaymentBtnSpinner').show();
                $('#createPaymentBtn').prop('disabled', true);
                var formData = $('#createPaymentForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/payments/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#createPaymentBtnSpinner').hide();
                        $('#createPaymentBtn').prop('disabled', false);
                        alert(response.message);
                        $('#datatable-detail-payment').DataTable().ajax.reload();
                        $('#paid').val('');
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        $('#createPaymentBtnSpinner').hide();
                        $('#createPaymentBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deletePayment = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/payments/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // getAlert(response.message);
                            $('#datatable-detail-payment').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };

        });
    </script>
@endpush

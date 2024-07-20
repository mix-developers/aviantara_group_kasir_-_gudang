@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card">
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
        </div>

        <div class="col-lg-8">
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
                            <button class="btn btn-secondary create-new btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#create">
                                <span>
                                    <i class="bx bx-plus me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Tambah Data</span>
                                </span>
                            </button>
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
                                <th><i class="bx bx-trash"></i></th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Metode Pembayaran</th>
                                <th>Dibayarkan</th>
                                <th>Penerima</th>
                                <th><i class="bx bx-trash"></i></th>
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
                    {
                        data: 'trash',
                        name: 'trash'
                    },
                ],
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ]
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

@push('js')
    <script>
        $(function() {
            $('#datatable-payment').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('order-wirehouses-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
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
                        data: 'tagihan',
                        name: 'tagihan'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'payment',
                        name: 'payment'
                    },
                    {
                        data: 'action_payment',
                        name: 'action_payment'
                    }
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-payment').DataTable().ajax.reload();
            });
            window.addPayment = function(id) {
                $('#create').modal('show');
                $('#idOrderWirehouse').val(id);
                getPaymentMethodOptions();
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
            window.sendOrderWirehouse = function(id) {
                $.ajax({
                    type: 'POST',
                    url: '/send_bill/' + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#datatable-payment').DataTable().ajax.reload();
                        getAlert(response.message);
                        window.open(response.whatsapp_url, '_blank');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
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
                        $('#datatable-payment').DataTable().ajax.reload();
                        getAlert(response.message);
                        $('#create').modal('hide');
                        $('#paid').val('');
                    },
                    error: function(xhr) {
                        $('#createPaymentBtnSpinner').hide();
                        $('#createPaymentBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
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
@endpush

@push('js')
    <script>
        $(function() {
            $('#datatable-payment-method').DataTable({
                processing: true,
                serverSide: false,
                responsive: false,
                ajax: '{{ url('paymentMethod-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'method',
                        name: 'method'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('.create-new').click(function() {
                $('#create').modal('show');
            });
            $('.refresh').click(function() {
                $('#datatable-payment-method').DataTable().ajax.reload();
                getPaymentCard();
            });
            window.editPaymentMethod = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/paymentMethod/edit/' + id,
                    success: function(response) {
                        $('#paymentMethodModalLabel').text('Edit Customer');
                        $('#formPaymentMethodId').val(response.id);
                        $('#formPaymentMethodMethod').val(response.method);
                        $('#paymentMethodModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#savepaymentMethodBtn').click(function() {
                var formData = $('#userForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/paymentMethod/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        $('#datatable-payment-method').DataTable().ajax.reload();
                        getPaymentCard().ajax.reload();
                        $('#paymentMethodModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createPaymentMethodBtn').click(function() {
                var formData = $('#createPaymentMethodForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/paymentMethod/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        $('#PaymentMethodsModalLabel').text('Edit PaymentMethod');
                        $('#formPaymentMethodMethod').val('');
                        $('#datatable-payment-method').DataTable().ajax.reload();
                        $('#create').modal('hide');
                        getPaymentCard().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deletePaymentMethod = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus metode ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/paymentMethod/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            getAlert(response.message);
                            $('#datatable-payment-method').DataTable().ajax.reload();
                            getPaymentCard();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };

            function getPaymentCard() {
                $.ajax({
                    url: '/paymentMethod/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#paymentCard').empty();

                        $.each(data, function(index, method) {
                            $.getJSON("get_total_payment_method/" + method.id, function(
                                respons) {
                                // console.log(respons.total);
                                $('#paymentCard').append(
                                    '<div class="col-md-3 col-6 mb-4"><div class="card"><div class="card-header">' +
                                    method.method +
                                    '</div><div class="card-body"><span class="h3 text-primary">Rp ' +
                                    formatNumberWithDot(respons.total) +
                                    '</span></div></div></div>'
                                );
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan: ' + error);
                    }
                });
            }

            function getAlert(alertValue) {
                $('#alert').append(
                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }

            function formatNumberWithDot(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            // getPaymentCard();
        });
    </script>
@endpush

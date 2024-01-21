@push('js')
    <script>
        $(function() {
            $('#datatable-payment-method').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
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
                        alert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-payment-method').DataTable().ajax.reload();
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
                        alert(response.message);
                        $('#PaymentMethodsModalLabel').text('Edit PaymentMethod');
                        $('#formPaymentMethodMethod').val('');
                        $('#datatable-payment-method').DataTable().ajax.reload();
                        $('#create').modal('hide');
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
                            // alert(response.message);
                            $('#datatable-payment-method').DataTable().ajax.reload();
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

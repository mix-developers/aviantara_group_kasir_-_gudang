@push('js')
    <script>
        $(function() {
            $('#datatable-shop').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('shops-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'shop',
                        name: 'shop'
                    },
                    {
                        data: 'address',
                        name: 'address'
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
                $('#datatable-shop').DataTable().ajax.reload();
            });
            window.editShop = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/shops/edit/' + id,
                    success: function(response) {
                        $('#shopsModalLabel').text('Edit Shop');
                        $('#formShopId').val(response.id);
                        $('#formShopName').val(response.name);
                        $('#formShopAddress').val(response.address);
                        $('#shopsModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveShopBtn').click(function() {
                var formData = $('#shopForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: '/shops/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-shop').DataTable().ajax.reload();
                        $('#shopsModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createShopBtn').click(function() {
                var formData = $('#createShopForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/shops/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        $('#customersModalLabel').text('Edit Shop');
                        $('#datatable-shop').DataTable().ajax.reload();
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteShop = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus toko ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/shops/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            getAlert(response.message);
                            $('#datatable-shop').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };

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

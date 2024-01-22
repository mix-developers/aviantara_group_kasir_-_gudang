@push('js')
    <script>
        $(function() {
            $('#datatable-product').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('products-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'produk',
                        name: 'produk'
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
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('.create-new').click(function() {
                $('#create').modal('show');
            });
            $('.refresh').click(function() {
                $('#datatable-product').DataTable().ajax.reload();
            });
            window.editProduct = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/products/edit/' + id,
                    success: function(response) {
                        $('#productsModalLabel').text('Edit Shop');
                        $('#formShopId').val(response.id);
                        $('#formShopName').val(response.name);
                        $('#formShopAddress').val(response.address);
                        $('#productsModal').modal('show');
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
                    url: '/products/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-product').DataTable().ajax.reload();
                        $('#productsModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createProductBtn').click(function() {
                var formData = $('#createProductForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/products/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#productsModalLabel').text('Edit products');
                        $('#datatable-product').DataTable().ajax.reload();
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteProduct = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/products/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // alert(response.message);
                            $('#datatable-product').DataTable().ajax.reload();
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

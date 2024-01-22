@push('js')
    <script>
        $(function() {
            $('#datatable-wirehouse').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('wirehouses-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'wirehouse',
                        name: 'wirehouse'
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
                $('#datatable-wirehouse').DataTable().ajax.reload();
            });
            window.editWirehouse = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/wirehouses/edit/' + id,
                    success: function(response) {
                        $('#wirehouseModalLabel').text('Edit Gudang');
                        $('#formWirehouseId').val(response.id);
                        $('#formWirehouseName').val(response.name);
                        $('#formWirehouseAddress').val(response.address);
                        $('#wirehousesModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveWirehouseBtn').click(function() {
                var formData = $('#wirehouseForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/wirehouses/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-wirehouse').DataTable().ajax.reload();
                        $('#wirehousesModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createWirehouseBtn').click(function() {
                var formData = $('#createWirehouseForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/wirehouses/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#wirehouseModalLabel').text('Edit Customer');
                        $('#formWirehouserName').val('');
                        $('#datatable-wirehouse').DataTable().ajax.reload();
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteWirehouse = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/wirehouses/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // alert(response.message);
                            $('#datatable-wirehouse').DataTable().ajax.reload();
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

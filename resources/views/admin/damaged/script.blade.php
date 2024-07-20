@push('js')
    <script>
        $(function() {
            $('#datatable-damageds').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('damageds-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
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
                $('#datatable-damageds').DataTable().ajax.reload();
            });
            window.editDamaged = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/customers/edit/' + id,
                    success: function(response) {
                        $('#customersModalLabel').text('Edit Customer');
                        $('#formCustomerId').val(response.id);
                        $('#formCustomerName').val(response.name);
                        $('#formCustomerPhone').val(response.phone);
                        $('#formCustomerAddressHome').val(response.address_home);
                        $('#formCustomerAddressCompany').val(response.address_company);
                        $('#customersModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveDamagedrBtn').click(function() {
                var formData = $('#damagedForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/damageds/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-customers').DataTable().ajax.reload();
                        $('#customersModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createDamagedBtn').click(function(e) {
                e.preventDefault();

                // Mengambil data dari form
                var formData = new FormData();
                formData.append('id_product', $('#formIdProduct').val());
                formData.append('photo', $('#formPhoto')[0].files[0]);
                formData.append('type', $('#formType').val());
                formData.append('total', $('#formTotal').val());
                formData.append('satuan', $('#formSatuan').val());
                formData.append('expired_date', $('#formExpiredDate').val());
                formData.append('description', $('#formDescription').val());

                // Mengirimkan permintaan AJAX
                $.ajax({
                    url: '{{ route('damageds.store') }}', // Sesuaikan dengan route yang sesuai
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $('#formIdProduct').val('');
                        $('#formPhoto').val('');
                        $('#formType').val('');
                        $('#formTotal').val('');
                        $('#formSatuan').val('');
                        $('#formExpiredDate').val('');
                        $('#formDescription').val('');
                        // Tambahkan logika lain sesuai kebutuhan, misalnya menampilkan pesan sukses
                        alert('Data berhasil disimpan!');
                        $('#create').modal('hide'); // Menutup modal setelah berhasil disimpan
                        $('#datatable-damageds').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        // console.error(xhr.responseText);
                        // Tambahkan logika untuk menampilkan pesan error, jika diperlukan
                        alert('Terjadi kesalahan saat menyimpan data. : ' + xhr.responseText);
                    }
                });
            });
            window.deleteDamaged = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/customers/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            getAlert(response.message);
                            $('#datatable-customers').DataTable().ajax.reload();
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

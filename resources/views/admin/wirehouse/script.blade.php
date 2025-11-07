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
                        data: 'logo',
                        name: 'logo'
                    },

                    {
                        data: 'wirehouse',
                        name: 'wirehouse'
                    },
                    {
                        data: 'ud_cv',
                        name: 'ud_cv'
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
                getWirehouseCard().ajax.reload();
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
                        $('#formWirehouseUdCv').val(response.ud_cv ?? '');

                        // tampilkan logo jika ada
                        if (response.logo) {
                            $('#formWirehouseLogoPreview').attr('src', '/storage/' + response.logo)
                                .show();
                        } else {
                            $('#formWirehouseLogoPreview').hide();
                        }
                        $('#wirehousesModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveWirehouseBtn').click(function() {
                var form = $('#wirehouseForm')[0];
                // var formData = $('#wirehouseForm').serialize();
                var formData = new FormData(form);
                $.ajax({
                    type: 'POST',
                    url: '/wirehouses/store',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // alert(response.message);
                        getAlert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-wirehouse').DataTable().ajax.reload();
                        $('#wirehousesModal').modal('hide');
                        getWirehouseCard().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createWirehouseBtn').click(function() {
                var form = $('#createWirehouseForm')[0];
                var formData = $('#createWirehouseForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/wirehouses/store',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // alert(response.message);
                        getAlert(response.message);
                        $('#wirehouseModalLabel').text('Edit Customer');
                        $('#formWirehouserName').val('');
                        $('#datatable-wirehouse').DataTable().ajax.reload();
                        $('#create').modal('hide');
                        getWirehouseCard().ajax.reload();
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
                            getAlert(response.message);
                            $('#datatable-wirehouse').DataTable().ajax.reload();
                            getWirehouseCard().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };

            function getWirehouseCard() {
                $.ajax({
                    url: '/wirehouses/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#wirehouseCard').empty();

                        $.each(data, function(index, wirehouse) {
                            $.getJSON("wirehouse-total-product/" + wirehouse.id, function(
                                respons) {
                                var totalProduk = respons.total;

                                var textClasss = (totalProduk === 0) ? 'text-danger' :
                                    'text-primary';

                                $('#wirehouseCard').append(
                                    '<div class="col-md-2 col-6 mb-4"><div class="card border border-primary"><div class="card-header" style="padding:10px;">' +
                                    wirehouse.name +
                                    '</div><div class="card-body" style="padding:10px;"> <span class = " ' +
                                    textClasss +
                                    ' h2"  > ' + totalProduk +
                                    ' </span>Produk </div></div> </div>'
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
            getWirehouseCard();
        });
    </script>
@endpush

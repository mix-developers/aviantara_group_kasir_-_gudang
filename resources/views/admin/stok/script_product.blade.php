@push('js')
    <script>
        var dataTable;
        $(function() {

            dataTable = $('#datatable-product').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('products-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        searchable: true
                    },
                    {
                        data: 'wirehouse',
                        name: 'wirehouse',
                        searchable: true
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'expired',
                        name: 'expired'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                columnDefs: [{
                    targets: [2],
                    searchable: true
                }, ],

            });

            $('#selectStok, #selectWirehouse').on('change', function() {
                applyFilters();
            });

            function applyFilters() {
                var stokFilter = $('#selectStok').val();
                var wirehouseFilter = $('#selectWirehouse').val();

                var newUrl = '{{ url('products-datatable') }}?stok=' + stokFilter + '&wirehouse=' + wirehouseFilter;
                dataTable.ajax.url(newUrl).load();
            }

            $('.create-new').click(function() {
                getWirehouseOptions();
                $('#create').modal('show');
            });
            $('.refresh').click(function() {
                $('#datatable-product').DataTable().ajax.reload();
            });

            function getUnitOptions(unitValue) {
                var staticData = ['Karung', 'Karton', 'Bal', 'Pak', 'Rak'];

                $('#formProductUnit').empty();

                $.each(staticData, function(index, unit) {
                    var selected = (unit === unitValue) ? 'selected' : '';
                    $('#formProductUnit').append('<option value="' + unit + '" ' + selected + '>' +
                        unit +
                        '</option>');
                });
            }

            function getSubUnitOptions(unitValue) {
                var staticData = ['Pcs', 'Ekor', 'Buah', 'Sacet', 'Botol', 'Gelas', 'Butir', 'Rim', 'Lembar',
                    'Gross', 'Lusin', 'Kodi', 'Bungkus'
                ];

                $('#formProductSubUnit').empty();

                $.each(staticData, function(index, sub_unit) {
                    var selected = (sub_unit === unitValue) ? 'selected' : '';
                    $('#formProductSubUnit').append('<option value="' + sub_unit + '" ' + selected + '>' +
                        sub_unit +
                        '</option>');
                });
            }

            function selectStok() {
                var data = ['Tersedia', 'Kosong'];

                $('#selectStok').empty();

                $('#selectStok').append('<option value="-" >Pilih Ketersediaan</option>');

                $.each(data, function(index, stok) {
                    $('#selectStok').append('<option value="' + stok + '" >' +
                        stok +
                        '</option>');
                });
            }

            function getWirehouseOptions(unitValue) {
                $.ajax({
                    url: '/wirehouses/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#formProductIdWirehouse').empty();
                        $('#formProductIdWirehouseCreate').empty();

                        $('#selectWirehouse').empty();
                        $('#selectWirehouse').append(
                            '<option value="-" >Pilih Gudang</option>');
                        $.each(data, function(index, wirehouse) {
                            $('#selectWirehouse').append('<option value="' +
                                wirehouse.id +
                                '" >' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');
                        });

                        $.each(data, function(index, wirehouse) {
                            $('#formProductIdWirehouseCreate').append(
                                '<option value="' +
                                wirehouse.id +
                                '" >' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');

                        });

                        $.each(data, function(index, wirehouse) {
                            var selected = (wirehouse.id === unitValue) ? 'selected' :
                                '';
                            $('#formProductIdWirehouse').append('<option value="' +
                                wirehouse
                                .id +
                                '" ' +
                                selected + '>' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan: ' + error);
                    }
                });
            }

            window.editProduct = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/products/edit/' + id,
                    success: function(response) {
                        $('#productsModalLabel').text('Edit Shop');
                        $('#formProductId').val(response.id);
                        $('#formProductName').val(response.name);
                        $('#formProductBarcode').val(response.barcode);
                        $('#formProductQUantityUnit').val(response.quantity_unit);
                        getUnitOptions(response.unit);
                        getSubUnitOptions(response.sub_unit);
                        getWirehouseOptions(response.id_wirehouse);
                        $('#productsModal').modal('show');

                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveProductBtn').click(function() {
                var formData = $('#productForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: '/products/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
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
                        getAlert(response.message);
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

                            getAlert(response.message);
                            $('#datatable-product').DataTable().ajax.reload();
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
            selectStok();
            getWirehouseOptions();
        });
    </script>
@endpush

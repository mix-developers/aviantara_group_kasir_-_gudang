@push('js')
    <script>
        $(document).ready(function() {
            //generate barcode
            $('#generateBarcodeBtn').on('click', function() {
                $.ajax({
                    url: '/generate-barcode',
                    type: 'GET',
                    beforeSend: function() {
                        $('#generateBarcodeBtn').prop('disabled', true).html(
                            'Generate');
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#formCreateProductBarcode').val(response.barcode);
                        } else {
                            alert('Gagal menghasilkan barcode.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menghasilkan barcode.');
                    },
                    complete: function() {
                        $('#generateBarcodeBtn').prop('disabled', false).html(
                            'Generate');
                    }
                });
            });
            // Reset form ketika tombol reset diklik
            $('#resetFormBtn').on('click', function() {
                $('#createProductForm')[0].reset(); // Reset form
                $('#formCreateProductBarcode').val(''); // Bersihkan input barcode
                $('#formCreateProductName').val(''); // Bersihkan input nama
                $('#formCreateProductUnit').val('Karung'); // Kembalikan ke opsi default
                $('#formCreateProductQUantityUnit').val('');
                $('#formCreateProductSubUnit').val('Kg');
            });
            $('#formCreateProductBarcode').on('change', function() {
                let barcode = $(this).val();

                if (barcode.trim() !== '') {
                    $.ajax({
                        url: '/search-product', // Sesuaikan dengan endpoint Laravel
                        type: 'GET',
                        data: {
                            barcode: barcode
                        },
                        beforeSend: function() {
                            $('#createProductBtnSpinner').show(); // Tampilkan spinner
                            $('#createProductBtn').prop('disabled',
                                true); // Disable tombol save
                        },
                        success: function(response) {
                            if (response.success) {
                                let product = response.data;
                                $('#formCreateProductName').val(product.name);
                                $('#formCreateProductUnit').val(product.unit);
                                $('#formCreateProductQUantityUnit').val(product.quantity_unit);
                                $('#formCreateProductSubUnit').val(product.sub_unit);
                            } else {
                                console.log(response.message);
                            }
                        },
                        error: function() {
                            console.log('Terjadi kesalahan saat mencari produk.');
                        },
                        complete: function() {
                            $('#createProductBtnSpinner').hide(); // Sembunyikan spinner
                            $('#createProductBtn').prop('disabled',
                                false); // Enable tombol save
                        }
                    });
                }
            });
        });
    </script>
    <script>
        var dataTable;
        $(function() {

            dataTable = $('#datatable-product').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('shop-products-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'barcode',
                        name: 'barcode',
                        searchable: true
                    },
                    {
                        data: 'name',
                        name: 'name',
                        searchable: true
                    },
                    {
                        data: 'isi',
                        name: 'isi',
                        searchable: true
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                        render: function(data, type, row) {
                            return data + ' ' + row.unit;
                        },
                        searchable: true
                    },
                    {
                        data: 'stock_retail',
                        name: 'stock_retail',
                        searchable: true
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
                    url: '/shop-products/edit/' + id,
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
                $('#saveProductBtnSpinner').show();
                $('#saveProductBtn').prop('disabled', true);
                var formData = new FormData($('#productForm')[
                    0]);
                $.ajax({
                    type: 'POST',
                    url: '/shop-products/store',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#saveProductBtnSpinner').hide();
                        $('#saveProductBtn').prop('disabled', false);
                        getAlert(response.message);
                        $('#formProductPhoto').val('');
                        $('#datatable-product').DataTable().ajax.reload();
                        $('#productsModal').modal('hide');
                    },
                    error: function(xhr) {
                        $('#saveProductBtnSpinner').hide();
                        $('#saveProductBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });

            $('#createProductBtn').click(function() {
                $('#createProductBtnSpinner').show();
                $('#createProductBtn').prop('disabled', true);
                var formData = new FormData($('#createProductForm')[
                    0]);
                $.ajax({
                    type: 'POST',
                    url: '/shop-products/store',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#createProductBtn').prop('disabled', false);
                        $('#createProductBtnSpinner').hide();
                        getAlert(response.message);
                        $('#datatable-product').DataTable().ajax.reload();
                        $('#formCreateProductName').val('');
                        $('#formCreateProductPhoto').val('');
                        $('#formCreateProductUnit').val('');
                        $('#formCreateProductSubUnit').val('');
                        $('#formCreateProductBarcode').val('');
                        $('#formCreateProductQUantityUnit').val('');
                        $('#formProductIdWirehouseCreate').val('');
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        $('#createProductBtnSpinner').hide();
                        $('#createProductBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteProduct = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/shop-products/delete/' + id,
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

            function expiredAlert() {
                $.ajax({
                    type: 'GET',
                    url: '/expired-alert',
                    success: function(response) {
                        var expiredText = '<span class="h4 text-danger"><i class="bx bx-error"></i> ' +
                            response.expired +
                            ' Barang pada gudang telah kadaluarsa ..</span>';
                        var remainingText = '<strong><i class="bx bx-error-circle"></i> ' + response
                            .remaining +
                            ' Barang pada gudang akan kadaluarsa..</strong><br><small>*Waktu peringatan dihitung 3 bulan sebelum tanggal kadaluarsa tiba.</small>';
                        if (response.expired != 0) {
                            getAlert2(expiredText, 'danger');
                        }
                        if (response.remaining != 0) {
                            getAlert2(remainingText, 'warning');

                        }
                    }
                });
            };

            function getAlert(alertValue) {
                $('#alert').append(
                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }

            function getAlert2(alertValue, type) {
                $('#alert').append(
                    '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }
            selectStok();
            getWirehouseOptions();
            expiredAlert();

        });
    </script>

    @include('admin.script.barcode_scanner')
@endpush

@push('js')
    <script>
        $(function() {
            var dataColumn = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'warning',
                    name: 'warning'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'product.name',
                    name: 'product.name'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'expired_date',
                    name: 'expired_date'
                },
                @if (Auth::user()->role != 'Gudang')
                    {
                        data: 'user',
                        name: 'user'
                    },
                @endif {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ];

            dataTable = $('#datatable-stok').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('stoks-datatable') }}',
                columns: dataColumn,
                // dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ]
            });
            $('.create-new').click(function() {
                getWirehouseOptions();
                $('#create').modal('show');
            });
            $('.refresh').click(function() {
                $('#datatable-stok').DataTable().ajax.reload();
                getStok().ajax.reload;
            });

            $('#selectUser, #selectTypeStok,#selectExpired,#fromDate, #toDate').on('change', function() {
                applyFilters();
            });

            function applyFilters() {
                var userFilter = $('#selectUser').val();
                var typeFilter = $('#selectTypeStok').val();
                var expiredFilter = $('#selectExpired').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();

                var newUrl = '{{ url('stoks-datatable') }}?type=' + typeFilter +
                    '&expired=' + expiredFilter + '&from-date=' + fromDate + '&to-date=' + toDate;

                dataTable.ajax.url(newUrl).load();

            }

            function getUnitOptions(unitValue) {
                var staticData = ['Karung', 'Karton', 'Bal', 'Pak'];

                $('#formProductUnit').empty();

                $.each(staticData, function(index, unit) {
                    var selected = (unit === unitValue) ? 'selected' : '';
                    $('#formProductUnit').append('<option value="' + unit + '" ' + selected + '>' +
                        unit +
                        '</option>');
                });
            }

            function getTypeStokOptions() {
                var staticData = ['Stok Masuk', 'Stok Keluar'];

                $('#selectTypeStok').empty();
                $('#selectTypeStok').append(
                    '<option value="-" >Pilih Jenis Stok</option>');

                $.each(staticData, function(index, type) {
                    $('#selectTypeStok').append('<option value="' +
                        type +
                        '" >' +
                        type +
                        '</option>');
                });;
            }

            function getExpiredOptions() {
                var staticData = ['Telah Kadaluarsa', 'Akan Kadaluarsa', 'Belum Kadaluarsa'];

                $('#selectExpired').empty();
                $('#selectExpired').append(
                    '<option value="-" >Pilih Status Kadaluarsa</option>');

                $.each(staticData, function(index, status) {
                    $('#selectExpired').append('<option value="' +
                        status +
                        '" >' +
                        status +
                        '</option>');
                });;
            }

            function getUserOptions() {
                $.ajax({
                    url: '/users/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#selectUser').empty();
                        $('#selectUser').append(
                            '<option value="-" >Pilih Pegawai</option>');

                        $.each(data, function(index, user) {
                            if (user.role === 'Gudang') {
                                $('#selectUser').append('<option value="' +
                                    user.id +
                                    '" >' +
                                    user.name + ' - ' + user.role + '</option>');
                            }
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan select user: ' + error);
                    }
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

                        $.each(data, function(index, wirehouse) {
                            $('#formProductIdWirehouseCreate').append('<option value="' +
                                wirehouse.id +
                                '" >' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');

                        });
                        $.each(data, function(index, wirehouse) {
                            var selected = (wirehouse.id === unitValue) ? 'selected' : '';
                            $('#formProductIdWirehouse').append('<option value="' + wirehouse
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

            window.editStok = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/stoks/edit/' + id,
                    success: function(response) {
                        $('#stoksModal').modal('show');
                        $('#productsModalLabel').text('Edit Stok');
                        $('#formStoktId').val(response.id);
                        $('#formProductId').val(response.id_product);
                        $('#formStokQuantity').val(response.quantity);
                        $('#formStokPrice').val(response.price_origin);
                        $('#formStokExpiredDate').val(response.expired_date);
                        $('#formStokUnit').text(response.product.unit);
                        $('#formStokUnit2').text('/' + response.product.unit);

                        $('#descriptionStok').empty();

                        $('#descriptionStok').append('<div class="list-group">' +
                            '<a href="javascript:void(0);" class="list-group-item list-group-item-action">' +
                            '<strong>Nama produk : </strong>' +
                            response.product.name +
                            '</a>' +
                            '<a href="javascript:void(0);" class="list-group-item list-group-item-action ">' +
                            '<strong>Barcode produk : </strong>' +
                            response.product.barcode +
                            '</a>' +
                            '</div>');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveStokBtn').click(function() {
                $('#saveStokBtnSpinner').show();
                $('#saveStokBtntBtn').prop('disabled', true);
                var formData = $('#stokForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: '/stoks/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#saveStokBtnSpinner').hide();
                        $('#saveStokBtntBtn').prop('disabled', false);
                        // getAlert(response.message);
                        $('#datatable-stok').DataTable().ajax.reload();
                        getStok();
                        $('#stoksModal').modal('hide');
                    },
                    error: function(xhr) {
                        $('#saveStokBtnSpinner').hide();
                        $('#saveStokBtntBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createStokBtn').click(function() {
                $('#createStokBtnSpinner').show();
                $('#createStokBtntBtn').prop('disabled', true);
                var formData = $('#createStokForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/stoks/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#createStokBtnSpinner').hide();
                        $('#createStokBtntBtn').prop('disabled', false);
                        // getAlert(response.message);
                        $('#datatable-stok').DataTable().ajax.reload();
                        getStok();

                        $('#formCreateStokBarcode').val('');
                        $('#formCreateStokPrice').val('');
                        $('#formCreateProductId').val('');
                        $('#formCreateStokQuantity').val('');
                        $('#formCreateStokExpiredDate').val('');
                        $('#formCreateStokUnit').text('Satuan');
                        $('#descriptionCreateStok').empty();
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        $('#createStokBtnSpinner').hide();
                        $('#createStokBtntBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteStok = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus stok ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/stoks/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // alert(response.message);
                            $('#datatable-stok').DataTable().ajax.reload();
                            getStok();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };

            function getStok() {
                $.ajax({
                    type: 'GET',
                    url: '/get-stok-card',
                    success: function(response) {

                        $('#stokInput').text(response.stok_input);
                        $('#stokOut').text(response.stok_out);
                        $('#stokExpired').text(response.stok_expired);
                        $('#stokNotExpired').text(response.stok_not_expired);
                        $('#stokWirehouse').text(response.stok_wirehouse);
                        $('#priceStokInput').text(formatNumberWithDot(response.price_stok_input));
                    }
                });
            };

            function formatNumberWithDot(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            getStok();
            getUserOptions();
            getTypeStokOptions();
            getExpiredOptions();
        });
        $(document).ready(function() {
            var selectedProduct = null;

            function selectProduct() {}
            $('.select-produk').click(function() {
                $('#productSelectionModal').modal('show');
            });
            $('#productSelectionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('products-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'barcode',
                        name: 'barcode'
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                ],
                select: {
                    blurable: true
                }
            });

            $('#productSelectionTable tbody').on('click', 'tr', function(e) {
                var selectedRowData = $('#productSelectionTable').DataTable().rows('.selected').data();

                let id = $(this).closest('tr').find('td:eq( 0 )').text();

                let name = $(this).closest('tr').find('td:eq( 1 )').text();
                let barcode = $(this).closest('tr').find('td:eq( 2 )').text();
                let unit = $(this).closest('tr').find('td:eq( 3 )').text();

                // console.log(name);
                $('.selectProduct').click(function() {
                    $('#formCreateProductId').val(id);
                    $('#formCreateStokName').val(name);
                    $('#formCreateStokUnit').text(unit);
                    $('#formCreateStokBarcode').val(barcode).prop('readonly', true);
                    $('#formCreateStokUnit2').text('/' + unit);

                    $('#productSelectionModal').modal('hide');
                    $('#descriptionCreateStok').empty();

                    $('#descriptionCreateStok').append('<div class="list-group">' +
                        '<a href="javascript:void(0);" class="list-group-item list-group-item-action">' +
                        '<strong>Nama produk : </strong>' +
                        name +
                        '</a>' +
                        '<a href="javascript:void(0);" class="list-group-item list-group-item-action ">' +
                        '<strong>Barcode produk : </strong>' +
                        barcode +
                        '</a>' +
                        '</div>');

                    // console.log('close modal');
                });
            });

        });
        $(document).ready(function() {
            $('.create-product').click(function() {
                getWirehouseOptions();
                $('#create-product').modal('show');
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


            function getWirehouseOptions(unitValue) {
                $.ajax({
                    url: '/wirehouses/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#formProductIdWirehouseCreate').empty();
                        $('#selectWirehouse').empty();
                        $('#selectWirehouse').append(
                            '<option value="-" >Pilih Gudang</option>');
                        $.each(data, function(index, wirehouse) {
                            $('#formProductIdWirehouseCreate').append(
                                '<option value="' +
                                wirehouse.id +
                                '" >' +
                                wirehouse.name + ' - ' + wirehouse.address +
                                '</option>');

                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan: ' + error);
                    }
                });
            }
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
                        $('#productSelectionTable').DataTable().ajax.reload();
                        $('#create-product').modal('hide');
                    },
                    error: function(xhr) {
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
            getWirehouseOptions();

        });
        $(document).ready(function() {
            $('#formCreateStokBarcode').on('input', function() {
                var barcode = $(this).val().trim();

                // Cek apakah barcode tidak kosong
                if (barcode !== '') {
                    // Lakukan AJAX request untuk mencari nama produk dan barcode produk
                    $.ajax({
                        url: '/products/scan', // Ganti dengan URL endpoint Anda
                        method: 'GET', // Metode request (GET, POST, dll)
                        data: {
                            barcode: barcode
                        }, // Data yang dikirimkan dalam request
                        success: function(response) {
                            // Response dari server berisi data nama dan barcode produk
                            var name = response
                                .name; // Ganti 'name' dengan nama variabel yang sesuai
                            var barcode = response
                                .barcode; // Ganti 'barcode' dengan nama variabel yang sesuai
                            console.log(response);
                            // Kosongkan konten sebelumnya di #descriptionCreateStok
                            $('#descriptionCreateStok').empty();
                            $('#formCreateStokBarcode').prop('readonly', true);
                            // Tambahkan informasi produk ke dalam #descriptionCreateStok
                            $('#descriptionCreateStok').append(
                                '<div class="list-group">' +
                                '<a href="javascript:void(0);" class="list-group-item list-group-item-action">' +
                                '<strong>Nama produk : </strong>' + name +
                                '</a>' +
                                '<a href="javascript:void(0);" class="list-group-item list-group-item-action">' +
                                '<strong>Barcode produk : </strong>' + barcode +
                                '</a>' +
                                '</div>'
                            );
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error); // Tangani kesalahan jika terjadi
                        }
                    });
                } else {
                    // Jika input barcode kosong, kosongkan juga konten di #descriptionCreateStok
                    $('#descriptionCreateStok').empty();
                }
            });

            // Jika form sudah terisi saat halaman dimuat, trigger event input untuk memeriksa barcode
            $('#formCreateStokBarcode').trigger('input');
        });
    </script>
    @include('admin.script.barcode_scanner')
@endpush

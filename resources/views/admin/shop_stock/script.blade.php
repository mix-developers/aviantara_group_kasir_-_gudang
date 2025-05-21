@push('js')
    <script>
        // Saat klik tombol "Pilih Produk"
        document.getElementById('selectProduct').addEventListener('click', function() {
            $('#selectProductModal').modal('show');
            $('#productSelectTable').DataTable().ajax.reload(); // reload data setiap buka
        });
        $(function() {
            // select produk
            $('#productSelectTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/shop-products-datatable', // Ganti dengan route API kamu
                columns: [{
                        data: 'barcode'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-success select-product-btn" 
                                    data-id="${row.id}" 
                                    data-name="${row.name}" 
                                    data-barcode="${row.barcode}">
                                Pilih
                            </button>`;
                        }
                    }
                ]
            });
            $(document).on('click', '.select-product-btn', function() {
                const id = $(this).data('id');
                // const name = $(this).data('name');
                const barcode = $(this).data('barcode');

                // $('#formProductBarcode').val(barcode);
                $('#formProductBarcode').val(barcode).trigger('input');

                $('#selectProductModal').modal('hide');
            });
            // stok
            $('#datatable-stok-kios').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ url('/shop-stok/getall') }}',
                    type: 'GET',
                    data: function(d) {
                        d.type = $('#filterType').val(); // ambil nilai dari dropdown
                    },
                    dataSrc: 'data'
                },
                columns: [

                    {
                        data: 'id',
                        name: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Mengembalikan nomor urut baris
                        }

                    },
                    {
                        data: 'product.barcode',
                        name: 'product.barcode',

                    },
                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        render: function(data, type, row) {
                            return data + ' ' + row.product.unit;
                        }
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data, type, row) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date',
                        render: function(data, type, row) {
                            var currentDate = moment().startOf('day');
                            // var currentDate = moment('2026-09-08').startOf('day');
                            if (currentDate.isBefore(data)) {
                                return '<strong class="h6 text-success">' + moment(data).format(
                                    'DD MMMM YYYY') + '</strong>';
                            } else {
                                return '<strong class="h6 text-danger">' + moment(data).format(
                                        'DD MMMM YYYY') +
                                    '</strong><br><small> (telah kadaluarsa)</small>';
                            }
                            // return moment(data).locale('id').format('DD MMMM YYYY');
                        }
                    },
                    @if (Auth::user()->role == 'Owner' || Auth::user()->role == 'Admin')
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                    @endif


                    {
                        data: null,
                        name: 'action',
                        title: 'Action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            console.log('ini id edit' + data.id);
                            return '<div class="btn-group d-flex"><button class="btn btn-sm btn-warning" onclick="editItem(' +
                                data.id +
                                ')">Edit</button> <button class="btn btn-sm btn-danger" onclick="hapusItem(' +
                                data.id +
                                ')">Hapus</button></div>';
                        }
                    },
                ],

                error: function(xhr, status, error) {
                    // Tangani kesalahan saat mengambil data
                    console.error(error);
                },


            });
            $('.btn-filter').on('click', function() {
                $('#datatable-stok-kios').DataTable().ajax.reload();
            });
            $('.create-new').click(function() {
                $('#create').modal('show');
            });

            $('.refresh').click(function() {
                $('#datatable-stok-kios').DataTable().ajax.reload();
            });


            window.editItem = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/shop-stok/edit/' + id,
                    success: function(response) {
                        console.log('ini response');
                        console.log(response.id);
                        $('#stokModalLabel').text('Edit Stok');
                        $('#editKodeProduk').val(response.product.barcode);
                        $('#editIdProduk').val(response.product.id);
                        $('#editStokId').val(response.id);
                        $('#editNamaKios').val(response.shop.name);
                        $('#editNamaProduk').val(response.product.name);
                        $('#editStokTipe').val(response.type);
                        $('#editStokQty').val(response.qty);
                        $('#editStokPrice').val(response.price);
                        $('#editStokExpired').val(response.expired_date);
                        $('#kios_stok_modal').modal('show');

                        //form nonaktif
                        $('#editNamaKios').prop('disabled', true);
                        $('#editNamaProduk').prop('disabled', true);
                        $('#editKodeProduk').prop('disabled', true);


                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    },
                });
            };


            $('#saveStokBtn').click(function() {
                var formData = $('#stokForm').serialize();
                console.log(formData);

                $.ajax({
                    type: 'POST',
                    url: '/shop-stok/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-stok-kios').DataTable().ajax.reload();
                        $('#kios_stok_modal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });


            $('#editStokBtn').click(function() {
                var formData = $('#editStokForm').serialize();

                console.log('edit');
                console.log(formData);

                $.ajax({
                    type: 'POST',
                    url: '/shop-stok/update',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        // $('#customersModalLabel').text('Edit Customer');
                        // $('#formCustomerName').val('');
                        // $('#datatable-customers').DataTable().ajax.reload();
                        // $('#create').modal('hide');

                        $('#datatable-stok-kios').DataTable().ajax.reload();
                        $('#kios_stok_modal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.hapusItem = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
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

        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + ribuan;
        }
    </script>

    <script>
        $(document).ready(function() {
            let debounceTimeout;

            $('#formProductBarcode').on('input', function() {
                clearTimeout(debounceTimeout);

                const barcode = $(this).val().trim();

                // Reset field produk agar tidak tampilkan data lama
                $('#formNamaProduk').val('Memuat...').removeClass('text-danger');
                $('#formIdProduk').val('');

                debounceTimeout = setTimeout(function() {
                    if (barcode !== '') {
                        $.ajax({
                            url: '/shop-stok/search',
                            type: 'GET',
                            data: {
                                'barcode': barcode
                            },
                            success: function(data) {
                                if (data && Object.keys(data).length > 0) {
                                    if (data.quantity_unit > 0) {
                                        $('#formIdProduk').val(data.id);
                                        $('#formNamaProduk').val(data.name).removeClass(
                                            'text-danger');
                                        $('#txtUnit').text(data.unit).show();
                                        $('#txtUnitPrice').text("/"+data.unit).show();
                                    } else {
                                        $('#formNamaProduk').val('Stok kosong')
                                            .addClass('text-danger');
                                    }
                                } else {
                                    $('#formNamaProduk').val('Data tidak ditemukan!')
                                        .addClass('text-danger');
                                }
                            },
                            error: function() {
                                $('#formNamaProduk').val('Terjadi kesalahan!').addClass(
                                    'text-danger');
                            }
                        });
                    } else {
                        $('#formNamaProduk').val('').removeClass('text-danger');
                        $('#formIdProduk').val('');
                        $('#txtUnit').text('').hide(); 
                        $('#txtUnitPrice').text('').hide(); 
                    }
                }, 400); // delay pencarian
            });
        });
    </script>
    @include('admin.script.barcode_scanner')
@endpush

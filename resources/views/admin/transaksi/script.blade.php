@push('js')
    <script>
        $(function() {
            $('#datatable-stok-kios').DataTable({
                processing: true,
                // serverSide: false,
                responsive: true,
                ajax: {
                    url: '{{ url('/transaksi/getall') }}',
                    type: 'GET',
                    // dataType: 'json',
                    dataSrc: 'data' // Nama properti yang berisi data dalam respons JSON
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
                        data: 'shop.name',
                        name: 'shop.name'
                    },
                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
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
                                    'DD MMMM YYYY') + ' (telah kadaluarsa)</strong>';
                            }
                            // return moment(data).locale('id').format('DD MMMM YYYY');
                        }
                    },

                    {
                        data: 'user.name',
                        name: 'user.name'
                    },


                    {
                        data: null,
                        name: 'action',
                        title: 'Action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            console.log('ini id edit' + data.id);
                            return '<div class="btn-group"><button class="btn btn-sm btn-warning" onclick="editItem(' +
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

            $('.create-new').click(function() {
                $('#create').modal('show');
            });

            $('.refresh').click(function() {
                $('#datatable-stok-kios').DataTable().ajax.reload();
            });


            window.editItem = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/kios_stok/edit/' + id,
                    success: function(response) {
                        console.log('ini response');
                        console.log(response.id);
                        $('#stokModalLabel').text('Edit Stok Kios');
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
                    url: '/kios_stok/store',
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
                    url: '/kios_stok/update',
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

        function getAlert(alertValue) {
            if (alertValue == 'success') {
                $('#alert').append(
                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            } else {
                $('#alert').append(
                    '<div class="alert alert-danger alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }
        }

        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + ribuan;
        }
    </script>


    {{-- TRANSAKSI --}}
    <script>
        $(document).ready(function() {
            function updateTotalPrice() {
                var total = 0;
                $('#cartTable tbody tr').each(function() {
                    var totalCell = $(this).find('.product-total').text();
                    total += parseFloat(totalCell);
                });
                $('#totalPrice').text(formatRupiah(total.toFixed()));
                $('#totalPriceInput').text(total.toFixed());
            }

            $('#bayar').on('input', function() {
                var bayar = $(this).val();
                var totalPrice = parseFloat($('#totalPriceInput').text());
                var kembalian = bayar - totalPrice;
                var bayarTampil = bayar;
                console.log(bayarTampil);
                $('#bayarTampil').text(formatRupiah(bayarTampil));
                if (kembalian < 0) {
                    $('#kembalian').text('-' + formatRupiah(kembalian.toFixed()));
                } else {
                    $('#kembalian').text(formatRupiah(kembalian.toFixed()));
                }
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                updateTotalPrice();
            });

            $('#barcodeInput').on('input', function() {
                var barcode = $(this).val();
                if (barcode.length >= 8) {
                    $.ajax({
                        url: '/transaksi/scan',
                        type: 'GET',
                        data: {
                            'barcode': barcode
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log(data.product.name);
                            if (data.qty == 0) {
                                alert('Stok habis');
                            } else {
                                var existingRow = $('#cartTable tbody tr').filter(function() {
                                    return $(this).find('input[name="product_id[]"]')
                                        .val() == data.id;
                                });
                            }

                            if (existingRow.length > 0) {
                                // Produk sudah ada, tambahkan jumlah kuantitas
                                var quantityInput = existingRow.find('input.quantity');
                                var newQuantity = parseInt(quantityInput.val()) + 1;
                                console.log(quantityInput.val());
                                if (data.qty < newQuantity) {
                                    alert('Stok tidak mencukupi');
                                } else {
                                    quantityInput.val(newQuantity);
                                    var newTotal = newQuantity * data.price;
                                    existingRow.find('.product-total').text(newTotal);

                                }
                            } else {

                                console.log(data.id);
                                var row = `<tr>
                                <td>${data.product.name}</td>
                                <td class="product-price">${data.price}</td>
                                <td><input type="number" name="quantity[]" class="form-control quantity" value="1" min="1"></td>
                                <td class="form control product-total">${data.price}</td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
                                <input type="hidden" name="product_id[]" value="${data.id}">
                                <input type="hidden" name="price[]" value="${data.price}">
                            </tr>`;
                                $('#cartTable tbody').append(row);
                            }
                            updateTotalPrice();
                            $('#barcodeInput').val('');
                        },
                        error: function() {
                            alert('Product not found');
                            $('#barcodeInput').val('');
                        }
                    });
                }
            });

            $(document).on('input', '.quantity', function() {
                var $row = $(this).closest('tr');
                var price = parseFloat($row.find('.product-price').text());
                var quantity = parseInt($(this).val());
                var total = price * quantity;
                $row.find('.product-total').text(total.toFixed(2));
                updateTotalPrice();
            });

            $('#transactionForm').on('submit', function(e) {
                e.preventDefault();

                var products = [];
                $('#cartTable tbody tr').each(function() {
                    var product_id = $(this).find('input[name="product_id[]"]').val();
                    var quantity = $(this).find('input[name="quantity[]"]').val();
                    var price = $(this).find('input[name="price[]"]').val();

                    products.push({
                        product_id: product_id,
                        quantity: quantity,
                        price: price,
                    });
                });
                console.log(products);
                var userId = @json(auth()->user()->id);
                $.ajax({
                    url: '/transaksi/store',
                    type: 'POST',
                    data: {
                        user_id: userId,
                        total_price: $('#totalPriceInput').text(),
                        products: products
                    },
                    // data: products,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (!data.success) {
                            getAlert(data.message + '  [' + data.nama_produk + ']');
                        } else {
                            console.log(data);
                            alert('Transaksi berhasil!');
                            location.reload();

                        }
                    },
                    error: function(response) {
                        alert('Transaksi gagal!');
                        console.log('Error:', response);
                        console.log(response.responseJSON);
                    }
                });
            });
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            // $('#formNamaProduk').prop('disabled', true);

            $('#barcodeInput').on('keyup', function() {
                
                var barcode = $(this).val();
                console.log(barcode);
                $.ajax({
                    url: '/transaksi/scan',
                    type: "GET",
                    data: {
                        'barcode': barcode
                    },
                    success: function(data) {
                        if (data && Object.keys(data).length > 0) {
                            console.log(data);
                            if (data) {
                                addRowToCart(data, data.price);
                                updateTotalPrice();
                            } else {
                                $('#formNamaProduk').val('stok kosong').addClass('text-danger');
                                console.log('scan transaksi dijalankan');
                            }
                        } else {
                            $('#formNamaProduk').val('data tidak ditemukan!').addClass(
                                'text-danger');
                            console.log('else besar dijalankan');

                        }
                    }
                });
            });
        });

        function addRowToCart(product, totalPrice) {
            const row = cartTable.insertRow();
            const productCell = row.insertCell();
            const priceCell = row.insertCell();
            const quantityCell = row.insertCell();
            const totalCell = row.insertCell();

            productCell.textContent = product.stok_kios.product.name;
            priceCell.textContent = product.stok_kios.price;
            quantityCell.textContent = 1;
            totalCell.textContent = totalPrice;
        }

        function updateTotalPrice() {
            let totalPrice = 0;
            const rows = cartTable.rows;

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const priceCell = row.cells[1]; // Assuming price is in the second cell (index 1)
                const quantityCell = row.cells[2]; // Assuming quantity is in the third cell (index 2)

                const price = parseFloat(priceCell.textContent);
                const quantity = parseInt(quantityCell.textContent);

                const itemTotalPrice = price * quantity;
                totalPrice += itemTotalPrice;
            }

            totalPriceElement.textContent = totalPrice.toFixed(2); // Format to two decimal places
        }
    </script> --}}

    {{-- <script>
        const barcodeInput = document.getElementById('barcodeInput');
        const cartTable = document.getElementById('cartTable');
        const totalPriceElement = document.getElementById('totalPrice');

        barcodeInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                console.log('test');
                scanBarcode();
            }
        });

        function scanBarcode() {
            const barcode = barcodeInput.value;
            barcodeInput.value = '';

            axios.get('/transaksi/scan', {
                    barcode: barcode
                })
                .then(response => {
                    const product = response.data.product;
                    const totalPrice = response.data.total_price;

                    addRowToCart(product, totalPrice);
                    updateTotalPrice();
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function addRowToCart(product, totalPrice) {
            const row = cartTable.insertRow();
            const productCell = row.insertCell();
            const priceCell = row.insertCell();
            const quantityCell = row.insertCell();
            const totalCell = row.insertCell();

            productCell.textContent = product.name;
            priceCell.textContent = product.price;
            quantityCell.textContent = 1;
            totalCell.textContent = totalPrice;
        }

        function updateTotalPrice() {
            let totalPrice = 0;
            const rows = cartTable.rows;

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const priceCell = row.cells[1]; // Assuming price is in the second cell (index 1)
                const quantityCell = row.cells[2]; // Assuming quantity is in the third cell (index 2)

                const price = parseFloat(priceCell.textContent);
                const quantity = parseInt(quantityCell.textContent);

                const itemTotalPrice = price * quantity;
                totalPrice += itemTotalPrice;
            }

            totalPriceElement.textContent = totalPrice.toFixed(2); // Format to two decimal places
        }
    </script> --}}
@endpush

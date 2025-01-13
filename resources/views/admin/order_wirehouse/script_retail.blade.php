<script>
    $(document).ready(function() {

        $('#selectOrderDiscount_retail').on('change', function() {
            const selectedMethod = $(this).val();

            if (selectedMethod === 'persen') {
                $('#divOrderDiscountPersen_retail').removeClass('d-none');
                $('#divOrderDiscountRupiah_retail').addClass('d-none');
                $('#orderDiscountRupiah_retail').val(0);
            } else if (selectedMethod === 'rupiah') {
                $('#divOrderDiscountRupiah_retail').removeClass('d-none');
                $('#divOrderDiscountPersen_retail').addClass('d-none');
                $('#orderDiscountPersen_retail').val(0);
            }
        });

        $('input[type="number"]').on('input', function() {
            if ($(this).val() < 0) {
                $(this).val(0);
            }
        });
    });
    $(function() {
        $('.create-new-retail').click(function() {
            $('#create_retail').modal('show');
            getWirehouseOptions();


        });
        $('#resetOrderBtn_retail').click(function() {
            $('#tableProductList_retail').empty();
            $('#totalOrder_retail').text('0');
            $('#total_fee_retail').val('0');
            $('#discountProductPersen_retail').val('0');
            $('#discountProductRupiah_retail').val('0');
            $('#orderDiscountPersen_retail').val('0');
            $('#orderDiscountRupiah_retail').val('0');
            $('#formCreateAdditionalFee_retail').val('');
            $('#formCreateAddressDelivery_retail').val('');
            $('#formCreateDescription_retail').val('');
            $('#formCreateDueDate_retail').val('');
            $('#descriptionCreateOrder_retail').empty();
            $('#createOrderForm_retail')[0].reset();
        });

        $('#createOrderBtn_retail').click(function() {
            $('#createOrderBtnSpinner_retail').show();
            $('#createOrderBtn_retail').prop('disabled', true);

            var formData = $('#createOrderForm_retail').serialize();

            $.ajax({
                type: 'POST',
                url: '/order_wirehouses/store',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#createOrderBtnSpinner_retail').hide();
                    $('#createOrderBtn_retail').prop('disabled', false);

                    $('#tableProductList_retail').empty();
                    $('#totalOrder_retail').text('0');
                    $('#total_fee_retail').val('0');
                    $('#discountProductPersen_retail').val('0');
                    $('#discountProductRupiah_retail').val('0');
                    $('#orderDiscountPersen_retail').val('0');
                    $('#orderDiscountRupiah_retail').val('0');
                    $('#formCreateAdditionalFee_retail').val('');
                    $('#formCreateAddressDelivery_retail').val('');
                    $('#formCreateDescription_retail').val('');
                    $('#formCreateDueDate_retail').val('');
                    $('#descriptionCreateOrder_retail').empty();
                    $('#createOrderForm_retail')[0].reset();
                    $('#datatable-order-wirehouse').DataTable().ajax.reload();
                    $('#create_retail').modal('hide');

                    // Tampilkan modal pembayaran
                    $('#create-payment_retail').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');

                    var total_tagihan = response.tagihan;
                    window.diskonItem = function(id, productName, subtotal) {
                        $('#discountProductRetail').modal('show');
                        $('#selectMethodDiscountRetail').on('change', function() {
                            const selectedMethod = $(this).val();

                            if (selectedMethod === 'persen') {
                                $('#discountPersenRetail').show();
                                $('#discountRupiahRetail').hide();
                                $('#discountProductRupiahRetail').val(0);
                            } else if (selectedMethod ===
                                'rupiah') {
                                $('#discountPersenRetail').hide();
                                $('#discountRupiahRetail').show();
                                $('#discountProductPersenRetail').val(0);
                            }
                            $currentRow = $(this).closest('tr');
                        });
                        $('#discountProductIdRetail').val(id);
                        $('#hargaSemulaRetail').val(response.tagihan);
                        $('#hargaSemulaItemRetail').val(subtotal);
                        $('#discountNameProductRetail').text(productName);

                        $('.discountBatalRetail').click(function() {
                            $('#discountProductRetail').modal('hide');

                        });
                        $('#applyDiscountButtonRetail').click(function() {
                            $('#applyDiscountButtonSpinnerRetail').show();
                            $('#applyDiscountButtonRetail').prop('disabled',
                                true);
                            var formData = $('#discountFormRetail').serialize();
                            $.ajax({
                                type: 'POST',
                                url: '/discount-order-items/store',
                                data: formData,
                                headers: {
                                    'X-CSRF-TOKEN': $(
                                            'meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                success: function(response) {
                                    $('#applyDiscountButtonSpinnerRetail')
                                        .hide();
                                    $('#applyDiscountButtonRetail')
                                        .prop(
                                            'disabled', false);
                                    $('#orderListTableRetail')
                                        .DataTable()
                                        .ajax.reload();
                                    $('#discountProductRetail')
                                        .modal(
                                            'hide');
                                    total_tagihan = response
                                        .new_total;
                                    $('#payment-tagihan-retail')
                                        .text(
                                            total_tagihan);

                                },
                                error: function(xhr, status, error) {
                                    $('#applyDiscountButtonSpinnerRetail')
                                        .hide();
                                    $('#applyDiscountButtonRetail')
                                        .prop(
                                            'disabled', false);
                                    alert(
                                        'Terjadi kesalahan: ' +
                                        error);
                                    $('#discountProductRetail')
                                        .modal(
                                            'hide');

                                }
                            });
                            $('#createOrderBtnSpinnerRetail').hide();
                            $('#createOrderBtnRetail').prop('disabled', false);
                        });

                    };
                    if ($.fn.dataTable.isDataTable('#orderListTableRetail')) {
                        $('#orderListTableRetail').DataTable().clear().destroy();
                    }
                    $('#orderListTableRetail').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{ url('order-item-datatable') }}/' + response.order,
                        searching: false,
                        paging: false,
                        info: false,
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'product.name',
                                name: 'product.name'
                            },
                            {
                                data: 'quantity',
                                name: 'quantity'
                            },
                            {
                                data: 'expired_date',
                                name: 'expired_date'
                            },
                            {
                                data: 'subtotal_text',
                                name: 'subtotal_text'
                            },
                            {
                                data: null,
                                name: 'button',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row) {
                                    return `
                                            <button type="button" class="btn text-primary btn-sm " onclick="diskonItem(${row.id}, '${row.product.name}', '${row.subtotal}')">
                                                <i class="bx bx-dollar-circle"></i> Diskon
                                            </button>
                                        `;
                                }
                            },
                        ]
                    });

                    $('#idOrderWirehouseRetail').val(response.order);
                    $('#payment-tagihan-retail').text(total_tagihan);
                    getPaymentMethodOptions();

                    function getPaymentMethodOptions() {
                        $.ajax({
                            url: '/paymentMethod/getall',
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#formProductIdWirehouseCreate').empty();

                                $('#selectPaymentMethodRetail').empty();
                                $.each(data, function(index, method) {
                                    $('#selectPaymentMethodRetail')
                                        .append(
                                            '<div class="form-check form-check-inline mt-3">' +
                                            '<input class="form-check-input" type="radio" name="id_payment_method" value="' +
                                            method.id +
                                            '"' +
                                            (index === 0 ? ' checked' :
                                                '') +
                                            // Opsi awal dipilih berdasarkan kondisi
                                            '>' +
                                            '</input><label class="form-check-label">' +
                                            method.method +
                                            '</label></div>'
                                        );
                                });

                            },
                            error: function(xhr, status, error) {
                                console.error('Terjadi kesalahan: ' + error);
                            }
                        });
                    }

                    $('#createPaymentBtn_retail').click(function() {
                        $('#createPaymentBtnSpinner_retail').show();
                        $('#createPaymentBtn_retail').prop('disabled', true);
                        var formData = $('#createPaymentFormRetail').serialize();

                        $.ajax({
                            type: 'POST',
                            url: '/payments/store',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                    .attr('content')
                            },
                            success: function(response) {
                                // alert(response.order);
                                window.open(
                                    '/order_wirehouses/print-invoice/' +
                                    response.order,
                                    'Print Invoice',
                                    'width=800,height=600'
                                )
                                $('#createPaymentBtnSpinner_retail')
                                    .hide();
                                $('#createPaymentBtn_retail').prop(
                                    'disabled',
                                    false);
                                $('#create-payment_retail').modal(
                                    'hide');
                                getAlert(
                                    'Berhasil membuat pesanan eceran'
                                );
                                $('#paid').val('');


                                // alert('Berhasil membuat order');
                            },
                            error: function(xhr) {
                                $('#createPaymentBtnSpinner_retail')
                                    .hide();
                                $('#createPaymentBtn_retail').prop(
                                    'disabled',
                                    false);
                                alert('Terjadi kesalahan: ' + xhr
                                    .responseText);
                            }
                        });
                    });
                    $('#createPaymentBtnNoPrint_retail').click(function() {
                        $('#createPaymentBtnNoPrintSpinner_retail').show();
                        $('#createPaymentBtnNoPrint_retail').prop('disabled', true);
                        var formData = $('#createPaymentFormRetail').serialize();

                        $.ajax({
                            type: 'POST',
                            url: '/payments/store',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                    .attr('content')
                            },
                            success: function(response) {
                                $('#createPaymentBtnNoPrintSpinner_retail')
                                    .hide();
                                $('#createPaymentBtnNoPrint_retail')
                                    .prop(
                                        'disabled',
                                        false);
                                $('#create-payment_retail').modal(
                                    'hide');
                                getAlert('Berhasil membuat pesanan');
                                $('#paid').val('');

                                // alert('Berhasil membuat order');
                            },
                            error: function(xhr) {
                                $('#createPaymentBtnNoPrintSpinner_retail')
                                    .hide();
                                $('#createPaymentBtnNoPrint_retail')
                                    .prop(
                                        'disabled',
                                        false);
                                alert('Terjadi kesalahan: ' + xhr
                                    .responseText);
                            }
                        });
                    });
                },
                error: function(xhr) {
                    $('#createOrderBtnSpinner_retail').hide();
                    $('#createOrderBtn_retail').prop('disabled', false);
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });

        function getAlert(alertValue) {
            const alertElement = $(
                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                alertValue +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>'
            );
            $('#alert').append(alertElement);
            setTimeout(function() {
                alertElement.alert('close');
            }, 5000);
        }

    });
    $(document).ready(function() {
        var selectCustomerRetail = null;

        // Modal Pilih Pelanggan for Retail
        $('.select-customer-retail').click(function() {
            $('#customerSelectionModalRetail').modal('show');
        });

        $('#customerSelectionTableRetail').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ url('customers-datatable') }}',
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'home',
                    name: 'home'
                },
                {
                    data: 'company',
                    name: 'company'
                },
            ],
            select: {
                blurable: true,
            },
        });

        $('#customerSelectionTableRetail tbody').on('click', 'tr', function() {
            var id = $(this).closest('tr').find('td:eq(0)').text();
            var name = $(this).closest('tr').find('td:eq(1)').text();
            var phone = $(this).closest('tr').find('td:eq(2)').text();
            var home = $(this).closest('tr').find('td:eq(3)').text();
            var company = $(this).closest('tr').find('td:eq(4)').text();

            if (id == null) {
                $('#createOrderBtn').prop('disabled', true);
            } else {
                $('#createOrderBtn').prop('disabled', false);
            }
            // console.log(name);
            $('.selectCustomerRetail').click(function() {
                $('#formCreateCustomerId_retail').val(id);

                $('#customerSelectionModalRetail').modal('hide');
                $('#descriptionCreateOrder_retail').empty();

                $('#descriptionCreateOrder_retail').append('<div class="list-group">' +
                    '<a href="javascript:void(0);" class="list-group-item list-group-item-action">' +
                    '<strong>Nama : </strong>' +
                    name +
                    '</a>' +
                    '<a href="javascript:void(0);" class="list-group-item list-group-item-action ">' +
                    '<strong>No. HP : </strong>' +
                    phone +
                    '</a>' +
                    '<a href="javascript:void(0);" class="list-group-item list-group-item-action ">' +
                    '<strong>Alamat Rumah : </strong>' +
                    home +
                    '</a>' +
                    '<a href="javascript:void(0);" class="list-group-item list-group-item-action ">' +
                    '<strong>Alamat Usaha : </strong>' +
                    company +
                    '</a>' +
                    '</div>');

                // console.log('close modal');
            });
        });

        // Modal Pilih Produk for Retail

    });
    $(document).ready(function() {
        var selectedProduct = null;

        function selectProduct() {}

        $('.select-product-retail').click(function() {
            $('#productSelectionModalRetail').modal('show');
        });
        $('#productSelectionTableRetail').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ url('prices-order-datatable') }}',
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
                    data: 'price_retail',
                    name: 'price_retail'
                },
                {
                    data: 'stok_retail',
                    name: 'stok_retail'
                },
            ],
            select: {
                blurable: true
            }
        });

        $('#productSelectionTableRetail tbody').on('click', 'tr', function(e) {
            var selectedRowData = $('#productSelectionTableRetail').DataTable().rows('.selected')
                .data();

            let id = $(this).closest('tr').find('td:eq(0)').text();
            let name = $(this).closest('tr').find('td:eq(1)').text();
            let barcode = $(this).closest('tr').find('td:eq(2)').text();
            let price = $(this).closest('tr').find('td:eq(3)').text();
            let stok = $(this).closest('tr').find('td:eq(4)').text();

            if (id == null) {
                $('#createOrderBtn_retail').prop('disabled', true);
            } else {
                $('#createOrderBtn_retail').prop('disabled', false);
            }
            if (stok == 0) {
                alert('Produk ini sedang kosong');
                return;
            }

            if (stok != 0) {
                $('.selectProductRetail').off('click').click(function() {
                    if ($('#tableProductList_retail').find(
                            'input[name="id_product[]"][value="' + id +
                            '"]').length > 0) {
                        alert('Produk ini sudah ada dalam daftar.');
                        return;
                    }
                    $('#productSelectionModalRetail').modal('hide');
                    $('#tableProductList_retail').find('tbody').empty();

                    getExpiredOptions(id, function(options) {
                        $('#tableProductList_retail').append(
                            '<tr><td>' +
                            name +
                            '<br><span class="price_retail text-warning fw-bold">Rp ' +
                            price +
                            '</span>' +
                            '<input type="hidden" name="id_product[]" value="' +
                            id +
                            '">' +
                            '<input type="hidden" name="price[]" value="' +
                            price +
                            '">' +
                            '<input type="hidden" class="total-val_retail" name="subtotal[]" value="">' +
                            '</td><td><input type="number" class="form-control form-control-sm quantity_retail" name="quantity[]" value="0" min="0" max="' +
                            stok + '"></td><td>' +
                            '<select name="expired_date[]" class="form-select form-select-sm text-capitalize" id="selectExpired">' +
                            options +
                            '</select>' +
                            '</td><td class="total_retail text-danger">0</td>' +
                            '<td><div class="d-flex"><button class="btn text-danger p-2 deleteProductRetail"><i class="text-danger bx bx-trash"></i></button></div></td></tr>'
                        );

                        // Use onchange to handle quantity input after the field loses focus
                        $('.quantity_retail').last().on('input', function() {
                            const $this = $(this);
                            let quantity = parseInt($this.val());

                            if (quantity > stok) {
                                alert('Melebihi stok yang tersedia!, maksimal pembelian : ' +
                                    stok);
                                $this.val(
                                    stok
                                );
                            }
                        });

                        $('.deleteProductRetail').click(function() {
                            event.preventDefault();
                            if (confirm(
                                    'Apakah Anda ingin menghapus produk ini?'
                                )) {
                                $(this).closest('tr').remove();
                                updateTotalRetail();
                            }
                        });
                        $('.quantity_retail').on('input', function() {
                            updateTotalRetail();
                        });

                    });
                });
            }
        });

        function updateTotalRetail() {
            let total = 0;

            $('#tableProductList_retail tr').each(function() {
                const price = parseFloat($(this).find('.price_retail').text().replace(/[^0-9.-]+/g,
                    "")) || 0;
                const quantity = parseInt($(this).find('.quantity_retail').val()) || 0;

                const subtotal = price * quantity;
                total += subtotal;

                $(this).find('.total_retail').text(formatNumberWithDot(subtotal));
                $(this).find('.total-val_retail').val(
                    subtotal);
            });

            $('#totalOrder_retail').text(formatNumberWithDot(total));
            $('#total_fee_retail').val(total);
        }

        function getExpiredOptions(id, callback) {
            $.ajax({
                url: '/stoks-expired-date/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = '';

                    $.each(data, function(index, expired) {
                        var currentDate = new Date().toISOString().split('T')[0];
                        var color = expired.expired_date <= currentDate ? 'text-danger' :
                            'text-success';
                        options += '<option class="' + color + '" value="' + expired
                            .expired_date + '">' +
                            expired.expired_date + '</option>';
                    });

                    callback(options);
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan expired: ' + error);
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('#formCreateDelivery_retail').change(function() {
            if (this.checked) {
                $('#hidden1_retail, #hidden2_retail').show();
            } else {
                $('#hidden1_retail, #hidden2_retail').hide();
            }
        });

    });

    function formatNumberWithDot(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>

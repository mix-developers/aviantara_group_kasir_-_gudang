@push('js')
    @include('admin.order_wirehouse.script_retail')
    <script>
        var dataTable;
        $(function() {
            dataTable = $('#datatable-order-wirehouse').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('order-wirehouses-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'no_invoice',
                        name: 'no_invoice'
                    },
                    {
                        data: 'customer.name',
                        name: 'customer.name'
                    },
                    {
                        data: 'wirehouse',
                        name: 'wirehouse'
                    },
                    {
                        data: 'total_fee_text',
                        name: 'total_fee_text'
                    },

                    {
                        data: 'delivery_text',
                        name: 'delivery_text'
                    },

                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('#selectDelivery,#selectWirehouse,#fromDate,#toDate').on('change', function() {
                applyFilters();
            });

            function applyFilters() {
                var deliveryFilter = $('#selectDelivery').val();
                var wirehouseFilter = $('#selectWirehouse').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();

                var newUrl = '{{ url('order-wirehouses-datatable') }}?delivery=' + deliveryFilter + '&wirehouse=' +
                    wirehouseFilter + '&from-date=' + fromDate + '&to-date=' + toDate;
                dataTable.ajax.url(newUrl).load();
            }

            function getDeliveryOptions() {
                var staticData = {
                    1: 'Pengantaran',
                    0: 'Ambil Ditempat'
                };

                $('#selectDelivery').empty();

                $('#selectDelivery').append('<option value="-">Pilih Pengantaran</option>');

                $.each(staticData, function(value, text) {
                    $('#selectDelivery').append('<option value="' + value + '">' + text + '</option>');
                });
            }

            function getWirehouseOptions() {
                $.ajax({
                    url: '/wirehouses/getall',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
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


                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan: ' + error);
                    }
                });
            }

            $('.create-new').click(function() {
                $('#create').modal('show');
                getWirehouseOptions();
                // discount
                $('#selectOrderDiscount').on('change', function() {
                    const selectedMethodOrderDiscount = $(this).val();

                    if (selectedMethodOrderDiscount === 'persen') {
                        $('#divOrderDiscountPersen').show();
                        $('#divOrderDiscountRupiah').hide();
                        $('#orderDiscountRupiah').val(0);
                    } else if (selectedMethodOrderDiscount ===
                        'rupiah') {
                        $('#divOrderDiscountPersen').hide();
                        $('#divOrderDiscountRupiah').show();
                        $('#orderDiscountPersen').val(0);
                    }
                });
                // -------
            });
            $('.create-customer').click(function() {
                $('#create-customer').modal('show');
            });
            $('#createCustomerBtn').click(function() {
                var formData = $('#createUserForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/customers/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#customersModalLabel').text('Edit Customer');
                        $('#formCustomerName').val('');
                        $('#formCustomerPhone').val('');
                        $('#formCustomerAddressHome').val('');
                        $('#formCustomerNik').val('');
                        $('#formCustomerAddressCompany').val('');
                        $('#customerSelectionTable').DataTable().ajax.reload();
                        $('#create-customer').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });

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

            $('.refresh').click(function() {
                $('#datatable-order-wirehouse').DataTable().ajax.reload();
            });
            window.editOrder = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/order_wirehouses/edit/' + id,
                    success: function(response) {
                        console.log(response.discount);
                        $('#paymentMethodModalLabel').text('Edit Customer');
                        $('#formEditId').val(response.id);
                        $('#EditInvoice').text(response.no_invoice);
                        $('#formEditCustomerId').val(response.id_customer);
                        $('#formEditDelivery').val(response.delivery);
                        $('#formEditDueDate').val(response.due_date);
                        $('#formEditDiscount').val(response.discount);
                        $('#formEditAdditionalFee').val(response.additional_fee);
                        // $('#formEditAddressDelivery').val(response.address_delivery);
                        if (response.delivery === 1) {
                            $('#formEditDelivery').prop('checked', true);
                        } else {
                            $('#formEditDelivery').prop('checked', false);
                        }
                        $('#formEditDescription').val(response.description);
                        //tampilkan identitas customer
                        $.getJSON("customers/getCustomer/" + response.id_customer, function(
                            response) {
                            $('#namaEditCustomer').text(response.name);
                            $('#namaEditNoHp').text(response.phone);
                            $('#namaEditAddress').text(response.address_home);
                            $('#namaEditAddressCompany').text(response.address_company);
                        });
                        $('#formEditDelivery').change(function() {
                            if ($(this).is(':checked')) {
                                $(this).val(
                                    '1'); // Mengatur nilai 1 jika checkbox dicentang
                            } else {
                                $(this).val(
                                    '0'); // Mengatur nilai 0 jika checkbox tidak dicentang
                            }
                        });
                        $('#formEditDelivery').change(function() {
                            if (response.delivery === 1 || this.checked) {
                                $('#hidden1, #hidden2').show();
                            } else {
                                $('#hidden1, #hidden2').hide();
                            }
                        });

                        $('#editModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveOrderBtn').click(function() {
                $('#saveOrderBtnSpinner').show();
                $('#saveOrderBtn').prop('disabled', true);

                var formData = $('#editOrderForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/order_wirehouses/update',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#saveOrderBtnSpinner').hide();
                        $('#saveOrderBtn').prop('disabled', false);
                        console.log(response.message);
                        // getAlert(response.message);

                        $('#datatable-order-wirehouse').DataTable().ajax.reload();
                        $('#editModal').modal('hide');
                    },
                    error: function(xhr) {
                        $('#saveOrderBtnSpinner').hide();
                        $('#saveOrderBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#resetOrderBtn').click(function() {
                // getAlert(response.message);
                $('#tableProductList').empty();
                $('#totalOrder').text('0');
                $('#total_fee').val('0');
                $('#discountProductPersen').val('0');
                $('#discountProductRupiah').val('0');
                $('#orderDiscountPersen').val('0');
                $('#orderDiscountRupiah').val('0');
                $('#formCreateAdditionalFee').val();
                $('#formCreateAddressDelivery').val();
                $('#formCreateDescription').val();
                $('#formCreateDueDate').val();
                $('#descriptionCreateOrder').empty();
                $('#createOrderForm')[0].reset();
                // console.log(response.order);
            });
            $('#createOrderBtn').click(function() {
                $('#createOrderBtnSpinner').show();

                $('#createOrderBtn').prop('disabled', true);
                var formData = $('#createOrderForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/order_wirehouses/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#createOrderBtnSpinner').hide();
                        $('#createOrderBtn').prop('disabled', false);
                        // getAlert(response.message);
                        $('#tableProductList').empty();
                        $('#totalOrder').text('0');
                        $('#total_fee').val('0');
                        $('#discountProductPersen').val('0');
                        $('#discountProductRupiah').val('0');
                        $('#orderDiscountPersen').val('0');
                        $('#orderDiscountRupiah').val('0');
                        $('#formCreateAdditionalFee').val();
                        $('#formCreateAddressDelivery').val();
                        $('#formCreateDescription').val();
                        $('#formCreateDueDate').val();
                        $('#descriptionCreateOrder').empty();
                        $('#createOrderForm')[0].reset();
                        $('#datatable-order-wirehouse').DataTable().ajax.reload();
                        $('#create').modal('hide');
                        // console.log(response.order);

                        // $('#create-payment').modal('show');
                        $('#create-payment').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).modal('show');


                        var total_tagihan = response.tagihan;
                        window.diskonItem = function(id, productName, subtotal) {
                            $('#discountProduct').modal('show');
                            $('#selectMethodDiscount').on('change', function() {
                                const selectedMethod = $(this).val();

                                if (selectedMethod === 'persen') {
                                    $('#discountPersen').show();
                                    $('#discountRupiah').hide();
                                    $('#discountProductRupiah').val(0);
                                } else if (selectedMethod ===
                                    'rupiah') {
                                    $('#discountPersen').hide();
                                    $('#discountRupiah').show();
                                    $('#discountProductPersen').val(0);
                                }
                                $currentRow = $(this).closest('tr');
                            });
                            $('#discountProductId').val(id);
                            $('#hargaSemula').val(response.tagihan);
                            $('#hargaSemulaItem').val(subtotal);
                            $('#discountNameProduct').text(productName);

                            $('.discountBatal').click(function() {
                                $('#discountProduct').modal('hide');

                            });
                            $('#applyDiscountButton').click(function() {
                                $('#applyDiscountButtonSpinner').show();
                                $('#applyDiscountButton').prop('disabled', true);
                                var formData = $('#discountForm').serialize();
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
                                        $('#applyDiscountButtonSpinner')
                                            .hide();
                                        $('#applyDiscountButton').prop(
                                            'disabled', false);
                                        $('#orderListTable').DataTable()
                                            .ajax.reload();
                                        $('#discountProduct').modal(
                                            'hide');
                                        total_tagihan = response
                                            .new_total;
                                        $('#payment-tagihan').text(
                                            total_tagihan);

                                    },
                                    error: function(xhr, status, error) {
                                        $('#applyDiscountButtonSpinner')
                                            .hide();
                                        $('#applyDiscountButton').prop(
                                            'disabled', false);
                                        console.error(
                                            'Terjadi kesalahan: ' +
                                            error);
                                        $('#discountProduct').modal(
                                            'hide');

                                    }
                                });
                                $('#createOrderBtnSpinner').hide();
                                $('#createOrderBtn').prop('disabled', false);
                            });

                        };
                        if ($.fn.dataTable.isDataTable('#orderListTable')) {
                            $('#orderListTable').DataTable().clear().destroy();
                        }
                        $('#orderListTable').DataTable({
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

                        $('#idOrderWirehouse').val(response.order);
                        $('#payment-tagihan').text(total_tagihan);
                        getPaymentMethodOptions();

                        function getPaymentMethodOptions() {
                            $.ajax({
                                url: '/paymentMethod/getall',
                                type: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    $('#formProductIdWirehouseCreate').empty();
                                    $('#selectPaymentMethod').empty();
                                    $.each(data, function(index, method) {
                                        $('#selectPaymentMethod').append(
                                            ' <div class="form-check form-check-inline mt-3"><input  class="form-check-input" type="radio" name="id_payment_method" value="' +
                                            method.id +
                                            '" >' +
                                            '</input><label class="form-check-label">' +
                                            method.method +
                                            '</label></div>');

                                    });

                                },
                                error: function(xhr, status, error) {
                                    console.error('Terjadi kesalahan: ' + error);
                                }
                            });
                        }


                        $('#createPaymentBtn').click(function() {
                            $('#createPaymentBtnSpinner').show();
                            $('#createPaymentBtn').prop('disabled', true);
                            var formData = $('#createPaymentForm').serialize();

                            $.ajax({
                                type: 'POST',
                                url: '/payments/store',
                                data: formData,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                success: function(response) {
                                    $('#createPaymentBtnSpinner').hide();
                                    $('#createPaymentBtn').prop('disabled',
                                        false);
                                    $('#create-payment').modal('hide');
                                    getAlert('Berhasil membuat pesanan');
                                    $('#paid').val('');

                                    window.open(
                                        '/order_wirehouses/print-invoice/' +
                                        response.order,
                                        'Print Invoice',
                                        'width=800,height=600'
                                    )
                                    // alert('Berhasil membuat order');
                                },
                                error: function(xhr) {
                                    $('#createPaymentBtnSpinner').hide();
                                    $('#createPaymentBtn').prop('disabled',
                                        false);
                                    alert('Terjadi kesalahan: ' + xhr
                                        .responseText);
                                }
                            });
                        });
                        $('#createPaymentBtnNoPrint').click(function() {
                            $('#createPaymentBtnNoPrintSpinner').show();
                            $('#createPaymentBtnNoPrint').prop('disabled', true);
                            var formData = $('#createPaymentForm').serialize();

                            $.ajax({
                                type: 'POST',
                                url: '/payments/store',
                                data: formData,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                success: function(response) {
                                    $('#createPaymentBtnNoPrintSpinner')
                                        .hide();
                                    $('#createPaymentBtnNoPrint').prop(
                                        'disabled',
                                        false);
                                    $('#create-payment').modal('hide');
                                    getAlert('Berhasil membuat pesanan');
                                    $('#paid').val('');

                                    // alert('Berhasil membuat order');
                                },
                                error: function(xhr) {
                                    $('#createPaymentBtnSpinner').hide();
                                    $('#createPaymentBtn').prop('disabled',
                                        false);
                                    alert('Terjadi kesalahan: ' + xhr
                                        .responseText);
                                }
                            });
                        });

                    },
                    error: function(xhr) {
                        $('#createOrderBtnSpinner').hide();
                        $('#createOrderBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteOrder = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/order_wirehouses/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            getAlert(response.message);
                            $('#datatable-order-wirehouse').DataTable().ajax.reload();
                            $('#productSelectionTable').DataTable().ajax.reload();
                            getPaymentCard().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };


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
            getDeliveryOptions();
            getWirehouseOptions();
        });
        $(document).ready(function() {
            var selectCustomer = null;

            function selectCustomer() {}
            $('.select-customer').click(function() {
                $('#customerSelectionModal').modal('show');
            });

            $('#customerSelectionTable').DataTable({
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
                    blurable: true
                }
            });

            $('#customerSelectionTable tbody').on('click', 'tr', function(e) {
                var selectedRowData = $('#customerSelectionTable').DataTable().rows('.selected').data();

                let id = $(this).closest('tr').find('td:eq( 0 )').text();

                let name = $(this).closest('tr').find('td:eq( 1 )').text();
                let phone = $(this).closest('tr').find('td:eq( 2 )').text();
                let home = $(this).closest('tr').find('td:eq( 3 )').text();
                let company = $(this).closest('tr').find('td:eq( 4 )').text();

                if (id == null) {
                    $('#createOrderBtn').prop('disabled', true);
                } else {
                    $('#createOrderBtn').prop('disabled', false);
                }
                // console.log(name);
                $('.selectCustomer').click(function() {
                    $('#formCreateCustomerId').val(id);

                    $('#customerSelectionModal').modal('hide');
                    $('#descriptionCreateOrder').empty();

                    $('#descriptionCreateOrder').append('<div class="list-group">' +
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

        });
        $(document).ready(function() {
            var selectedProduct = null;

            function selectProduct() {}

            $('.select-product').click(function() {
                $('#productSelectionModal').modal('show');
            });
            $('#productSelectionTable').DataTable({
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
                        data: 'price_grosir',
                        name: 'price_grosir'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                ],
                select: {
                    blurable: true
                }
            });

            $('#productSelectionTable tbody').on('click', 'tr', function(e) {
                var selectedRowData = $('#productSelectionTable').DataTable().rows('.selected').data();

                let id = $(this).closest('tr').find('td:eq(0)').text();
                let name = $(this).closest('tr').find('td:eq(1)').text();
                let barcode = $(this).closest('tr').find('td:eq(2)').text();
                let price = $(this).closest('tr').find('td:eq(3)').text();
                let stok = $(this).closest('tr').find('td:eq(4)').text();

                if (id == null) {
                    $('#createOrderBtn').prop('disabled', true);
                } else {
                    $('#createOrderBtn').prop('disabled', false);
                }
                if (stok == 0) {
                    alert('Produk ini sedang kosong');
                    return;
                }

                if (stok != 0) {
                    $('.selectProduct').off('click').click(function() {
                        if ($('#tableProductList').find('input[name="id_product[]"][value="' + id +
                                '"]').length > 0) {
                            alert('Produk ini sudah ada dalam daftar.');
                            return;
                        }
                        $('#productSelectionModal').modal('hide');
                        $('#tableProductList').find('tbody').empty();

                        getExpiredOptions(id, function(options) {
                            $('#tableProductList').append(
                                '<tr><td>' +
                                name +
                                '<br><span class="price text-warning fw-bold">Rp ' +
                                price +
                                '</span>' +
                                '<input type="hidden" name="id_product[]" value="' +
                                id +
                                '">' +
                                '<input type="hidden" name="price[]" value="' +
                                price +
                                '">' +
                                '<input type="hidden" class="total-val" name="subtotal[]" value="">' +
                                '</td><td><input type="number" class="form-control form-control-sm quantity" name="quantity[]" value="0" min="0" max="' +
                                stok + '"></td><td>' +
                                '<select name="expired_date[]" class="form-select form-select-sm text-capitalize" id="selectExpired">' +
                                options +
                                '</select>' +
                                '</td><td class="total text-danger">0</td>' +
                                '<td><div class="d-flex"><button class="btn text-danger p-2 deleteProduct"><i class="text-danger bx bx-trash"></i></button></div></td></tr>'
                            );

                            // Use onchange to handle quantity input after the field loses focus
                            $('.quantity').last().on('input', function() {
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

                            $('.deleteProduct').click(function() {
                                event.preventDefault();
                                if (confirm(
                                        'Apakah Anda ingin menghapus produk ini?'
                                    )) {
                                    $(this).closest('tr').remove();
                                    updateTotal();
                                }
                            });
                            $('.quantity').on('input', function() {
                                updateTotal();
                            });

                        });
                    });
                }
            });

            function updateTotal() {
                let total = 0;

                $('#tableProductList tr').each(function() {
                    const price = parseFloat($(this).find('.price').text().replace(/[^0-9.-]+/g, "")) || 0;
                    const quantity = parseInt($(this).find('.quantity').val()) || 0;

                    const subtotal = price * quantity;
                    total += subtotal;

                    $(this).find('.total').text(formatNumberWithDot(subtotal));
                    $(this).find('.total-val').val(
                        subtotal);
                });

                $('#totalOrder').text(formatNumberWithDot(total));
                $('#total_fee').val(total);
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
            $('#formCreateDelivery').change(function() {
                if (this.checked) {
                    $('#hidden1, #hidden2').show();
                } else {
                    $('#hidden1, #hidden2').hide();
                }
            });

        });

        function formatNumberWithDot(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>
@endpush

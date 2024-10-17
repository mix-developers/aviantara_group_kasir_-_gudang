@push('js')
    <script>
        var dataTable;
        $(function() {
            dataTable = $('#datatable-price').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('prices-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'wirehouse',
                        name: 'wirehouse',
                        searchable: true
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'grosir',
                        name: 'grosir'
                    },
                    {
                        data: 'percentese_fee',
                        name: 'percentese_fee'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-price').DataTable().ajax.reload();
            });

            $('#selectWirehouse').on('change', function() {
                applyFilters();
            });

            function applyFilters() {
                var wirehouseFilter = $('#selectWirehouse').val();

                var newUrl = '{{ url('prices-datatable') }}?&wirehouse=' + wirehouseFilter;
                dataTable.ajax.url(newUrl).load();
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

            window.editPrice = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/prices/edit/' + id,
                    success: function(response) {
                        $('#shopsModalLabel').text('Edit Shop');
                        $('#formIdProduct').val(response.id);
                        $('#formPriceGrosirUnit').text('/' + response.unit);
                        $('#formPriceGrosirSubUnit').text('/' + response.sub_unit);
                        $('#pricesModal').modal('show');


                        $('#descriptionStok').empty();
                        $('#descriptionStok').append('<div class="list-group">' +
                            '<a href="javascript:void(0);" class="list-group-item list-group-item-action">' +
                            '<strong>Nama produk : </strong>' +
                            response.name +
                            '</a>' +
                            '<a href="javascript:void(0);" class="list-group-item list-group-item-action ">' +
                            '<strong>Barcode produk : </strong>' +
                            response.barcode +
                            '</a>' +
                            '</div>');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#savePriceBtn').click(function() {
                var formData = $('#priceForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: '/prices/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getAlert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#formPriceGrosir').val('');
                        $('#datatable-price').DataTable().ajax.reload();
                        notPriceAlert();
                        $('#pricesModal').modal('hide');
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

            function getAlertDanger(alertValue) {
                $('#alert').append(
                    '<div class="alert alert-danger alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }

            function notPriceAlert() {
                $.ajax({
                    type: 'GET',
                    url: '/prices/get-not-price',
                    success: function(response) {
                        var text = '<strong><i class="bx bx-error-circle"></i> ' + response +
                            ' Produk belum diberikan harga</small>';
                        if (response !== 0) {
                            getAlertDanger(text);

                        }
                    }
                });
            };
            notPriceAlert();
            getWirehouseOptions();
        });
    </script>
@endpush

@push('js')
    <script>
        $(function() {
            var table = $('#datatable-damageds').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('damageds-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'wirehouse.name',
                        name: 'wirehouse.name'
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
                        data: 'quantity_unit',
                        name: 'quantity_unit'
                    },
                    {
                        data: 'quantity_sub_unit',
                        name: 'quantity_sub_unit'
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date'
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
            $('#filterBtn').click(function() {
                var selectType = $('#selectType').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();

                var newUrl = '{{ url('damageds-datatable') }}?type=' +
                    selectType + '&from-date=' + fromDate + '&to-date=' + toDate;
                table.ajax.url(newUrl).load();

            });

            window.showDamaged = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/damageds/edit/' + id,
                    success: function(response) {

                        $('#photo1').attr('src', response.photo_url);
                        if (response.photo2 != null) {
                            $('#photo2').attr('src', response.photo2_url).show();
                        } else {
                            $('#photo2').hide();
                        }
                        $('#description_show').text(response.description);

                        // Show the modal
                        $('#show').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#createDamagedBtn').click(function(e) {
                $('#createDamagedBtnSpinner').show();
                $('#createDamagedBtn').prop('disabled', true);
                e.preventDefault();

                // Ambil semua data dari form
                var form = $('#createDamagedForm')[0]; // Ganti #formDamaged dengan ID form Anda
                var formData = new FormData(form); // Mengambil semua data dari form secara dinamis

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
                        $('#createDamagedBtnSpinner').hidden();
                        $('#createDamagedBtn').prop('disabled', false);
                        console.log(response);
                        $('#createDamagedForm')[0].reset();
                        $('#create').modal('hide');
                        $('#datatable-damageds').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        $('#createDamagedBtnSpinner').hidden();
                        $('#createDamagedBtn').prop('disabled', false);
                        alert('Terjadi kesalahan saat menyimpan data. : ' + xhr.responseText);
                    }
                });
            });
            window.deleteDamaged = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/damageds/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            getAlert(response.message);
                            $('#datatable-damageds').DataTable().ajax.reload();
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
                    {
                        data: 'sub_unit',
                        name: 'sub_unit'
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
                let sub_unit = $(this).closest('tr').find('td:eq( 4 )').text();

                // console.log(name);
                $('.selectProduct').click(function() {
                    $('#createFormIdProduct').val(id);
                    $('#formCreateStokName').val(name);
                    $('#formCreateStokUnit').text('/' + unit);
                    $('#formCreateStokBarcode').val(barcode).prop('readonly', true);
                    $('#formCreateStokUnit2').text('/' + unit);
                    $('#formCreateSubUnit').text('/' + sub_unit);
                    $('#formCreateSubUnit2').text('/' + sub_unit);

                    getExpiredOptions(id);

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

            function getExpiredOptions(id) {
                $.ajax({
                    url: '/stoks-expired-date/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#selectExpired').empty();
                        $.each(data, function(index, expired) {
                            $('#selectExpired').append('<option value="' +
                                expired.expired_date + '">' + expired.expired_date +
                                '</option>');

                        });

                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan select user: ' + error);
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Event listener untuk perubahan pada dropdown
            $('#formType').on('change', function() {
                var selectedValue = $(this).val();

                // Jika nilai yang dipilih adalah "Rusak", tampilkan #jeniRusak
                if (selectedValue === 'Rusak') {
                    $('#jeniRusak').show();
                } else {
                    // Jika tidak, sembunyikan
                    $('#jeniRusak').hide();
                }
            });

            // Jalankan sekali pada halaman load untuk memastikan kondisi awal
            $('#formType').trigger('change');
        });
    </script>
@endpush

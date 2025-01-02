@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <span id="alert">

    </span>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }} - <span
                                class="text-danger">{{ $wirehouse->name }}</span></h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class=" btn-group " role="group">
                            <button class="btn btn-secondary refresh btn-default" type="button">
                                <span>
                                    <i class="bx bx-sync me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Refresh Data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-months" class="table table-sm table-hover display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- modal --}}
    <!-- Modal for Create and Edit -->
    <div class="modal fade" id="opnameModal" tabindex="-1" aria-labelledby="paymentMethodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Opname <span id="monthYear"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for Create and Edit -->
                    <small class="text-danger">*Harap berikan keterangan jika selisih lebih besar atau kurang dari 0
                        <br>
                        <ul>
                            <li>G = Grosir</li>
                            <li>R = Retail</li>
                        </ul>
                    </small>
                    <table id="datatable-product" class="table table-hover table-bordered display table-sm">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2">ID</th>
                                <th rowspan="2">Nama</th>
                                <th colspan="2">Selisih</th>
                                <th colspan="2">Stok Sistem</th>
                                <th colspan="2">Stok Asli</th>
                                <th rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th>G</th>
                                <th>R</th>
                                <th>G</th>
                                <th>R</th>
                                <th>G</th>
                                <th>R</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ url('opname-wirehouse-item/pdf', [
                        'month' => Carbon\Carbon::now()->month,
                        'year' => Carbon\Carbon::now()->year,
                        'wirehouse' => $wirehouse->id,
                    ]) }}"
                        target="_blank" class="btn btn-danger">
                        <i class="bx bxs-file-pdf"></i> Download Data Terakhir
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            const table = $('#datatable-months').DataTable({
                processing: true,
                serverSide: true, // Gunakan ini jika ada server-side processing
                ajax: {
                    url: '{{ route('data.months') }}', // Sesuaikan dengan URL sumber data
                    type: 'GET',
                    data: function(d) {
                        d.wirehouse =
                            '{{ $wirehouse->id }}';
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'year',
                        name: 'year'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                pageLength: 13,
            });

            // Tombol Refresh
            $('.refresh').on('click', function() {
                table.ajax.reload(null, false); // Reload tanpa reset pagination
            });
            window.opnameWirehouse = function(month, year) {
                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                // Konversi angka bulan ke nama bulan (array dimulai dari 0)
                const monthName = monthNames[month - 1];
                if ($.fn.DataTable.isDataTable('#datatable-product')) {
                    $('#datatable-product').DataTable().destroy(); // Hancurkan DataTable jika sudah ada
                }
                dataTable = $('#datatable-product').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    scrollX: true,
                    ajax: {
                        url: '{{ url('products-datatable') }}',
                        data: function(d) {
                            d.wirehouse =
                                '{{ $wirehouse->id }}';
                        }
                    },
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
                            data: 'opname_selisih',
                            name: 'opname_selisih',
                        },
                        {
                            data: 'opname_selisih_retail',
                            name: 'opname_selisih_retail',
                        },
                        {
                            data: 'stok_text',
                            name: 'stok_text',
                        },
                        {
                            data: 'stok_text_retail',
                            name: 'stok_text_retail',
                        },

                        {
                            data: 'id', // Kolom ID produk untuk referensi
                            name: 'qty',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                                <input type="number" class="form-control editable-qty" 
                                    data-id="${data}" min="0"  value="${row.opname_qty || 0}" placeholder="0" style="width: 80px;"/>`;
                            }
                        },
                        {
                            data: 'id', // Kolom ID produk untuk referensi
                            name: 'qty_retail',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                                <input type="number" class="form-control editable-qty-retail" 
                                    data-id="${data}" min="0"  value="${row.opname_qty_retail || 0}" placeholder="0" style="width: 80px;"/>`;
                            }
                        },
                        {
                            data: 'id', // Kolom ID produk untuk referensi
                            name: 'description',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                                <input type="text" class="form-control editable-description" 
                                    data-id="${data}" value="${row.opname_description || '-'}" placeholder="Deskripsi..." />`;
                            }
                        }
                    ],

                });

                $('#monthYear').text('Bulan  : ' + monthName + ' ' + year);
                $('#opnameModal').modal('show');
            };
            window.viewWirehouse = function(month, year) {
                window.open('{{ url('opname-wirehouse-item/pdf') }}/' + month + '/' + year +
                    '/{{ $wirehouse->id }}', '_blank');
            };
        });
        $(document).on('change', '.editable-qty,.editable-qty-retail, .editable-description', function() {
            const row = $(this).closest('tr'); // Ambil baris tabel tempat input berada
            const productId = row.find('.editable-qty').data('id'); // ID produk
            const quantity = row.find('.editable-qty').val(); // Nilai quantity
            const quantity_retail = row.find('.editable-qty-retail').val(); // Nilai quantity
            const description = row.find('.editable-description').val(); // Nila

            // Validasi input
            if (quantity < 0 || isNaN(quantity)) {
                alert('Jumlah tidak valid!');
                return;
            }

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: '{{ url('/opname-wirehouse-item/store') }}', // Endpoint untuk menyimpan qty
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF
                    id_product: productId,
                    id_wirehouse: '{{ $wirehouse->id }}',
                    id_user: '{{ Auth::id() }}',
                    qty_real: quantity,
                    qty_real_retail: quantity_retail,
                    description: description
                },
                success: function(response) {
                    alert('Jumlah produk berhasil disimpan!');
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat menyimpan data!');
                }
            });
        });
    </script>
@endpush

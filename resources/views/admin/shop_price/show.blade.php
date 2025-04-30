@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <canvas id="priceHistoryChart" height="80px"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }}</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <button class="btn btn-secondary refresh btn-default" type="button">
                            <i class="bx bx-sync me-sm-1"></i>
                        </button>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-detail-price" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Tanggal</th>
                                <th>Harga Grosir</th>
                                <th>Pegawai</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Tanggal</th>
                                <th>Harga Grosir</th>
                                <th>Pegawai</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function() {
            const productId = {{ $product->id }};

            function fetchPriceHistory() {
                $.get(`{{ url('shop-price-history') }}/${productId}`, function(response) {
                    const labels = response.map(item => item.created_at);
                    const prices = response.map(item => item.price);

                    const ctx = document.getElementById('priceHistoryChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Harga Grosir',
                                data: prices,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Tanggal'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Harga'
                                    }
                                }
                            }
                        }
                    });
                });
            }

            fetchPriceHistory();

            $('#datatable-detail-price').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('shop-price-detail-datatable', $product->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'product.name',
                        name: 'product.name'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'grosir',
                        name: 'grosir'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    }
                ],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="bx bxs-file-pdf"></i> PDF',
                        className: 'btn-danger mx-3',
                        orientation: 'landscape',
                        title: '{{ $title . date('d-m-y H:i:s') }}',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bx bxs-file-export"></i> Excel',
                        title: '{{ $title . date('d-m-y H:i:s') }}',
                        className: 'btn-success',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });

            $('.refresh').click(function() {
                $('#datatable-detail-price').DataTable().ajax.reload();
                fetchPriceHistory();
            });
        });
    </script>
    <!-- JS DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush

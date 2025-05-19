@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="" id="alert"></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">

                <div class="row mb-3 align-items-end justify-content-center mx-2 py-1 ">
                    <div class="col-md-5 mb-2 text-center">
                        <label for="from-date">Rentang Tanggal</label>
                        <div class="input-group">
                            <button class="btn btn-sm btn-outline-primary" id="today-btn">Today</button>
                            <button class="btn btn-sm btn-outline-primary" id="week-btn">Week</button>
                            <input type="date" class="form-control datepicker" name="from-date" id="from-date"
                                value="{{ date('Y-m-d', strtotime('-7 day')) }}" placeholder="From Date">
                            <input type="date" class="form-control datepicker" name="to-date" id="to-date"
                                value="{{ date('Y-m-d') }}" placeholder="To Date">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button" class="btn btn-primary w-100" id="filter-button">
                            <i class="bx bx-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="mb-2 p-2 bg-white rounded shadow-sm">
                <h1 class="fw-bold text-primary" id="total_sub_total">Pendapatan : Rp</h1>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table id="historyTable" class="table table-striped table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Cetak</th>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Sub total</th>
                                <th>Dibayarkan</th>
                                <th>Kembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi dengan AJAX -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Cetak</th>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Sub total</th>
                                <th>Dibayarkan</th>
                                <th>Kembalian</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- cetak struct --}}
    @include('admin.shop_orders.components.struk')
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const todayBtn = document.getElementById('today-btn');
            const weekBtn = document.getElementById('week-btn');
            const fromDate = document.getElementById('from-date');
            const toDate = document.getElementById('to-date');

            // Fungsi format tanggal ke yyyy-mm-dd
            function formatDate(date) {
                return date.toISOString().split('T')[0];
            }

            // Saat tombol "Today" diklik
            todayBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const today = new Date();
                fromDate.value = formatDate(today);
                toDate.value = formatDate(today);
            });

            // Saat tombol "Week" diklik
            weekBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const today = new Date();
                const sevenDaysAgo = new Date();
                sevenDaysAgo.setDate(today.getDate() - 6); // total 7 hari termasuk hari ini
                fromDate.value = formatDate(sevenDaysAgo);
                toDate.value = formatDate(today);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let orderTable = $('#historyTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ url('shop-order-datatable') }}",
                    type: 'GET',
                    cache: false,
                    data: function(d) {
                        d['from-date'] = $('#from-date').val();
                        d['to-date'] = $('#to-date').val();
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row) {
                            return `
                                    <button 
                                        onclick="printStrukUlang(this)" 
                                        class="btn btn-sm btn-primary"
                                        data-id-order="${row.id}" 
                                        data-id-invoice="${row.no_invoice}" 
                                        data-id-total="${row.total_fee}"
                                        data-id-paid="${row.payment_received}"
                                        data-id-change="${row.change}"
                                        data-id-metode="${row.order_shop_payment[0]?.payment_method?.method ?? '-'}"
                                        >
                                        <i class="bx bx-printer"></i>
                                    </button>
                                `;
                        }
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
                        data: 'total_fee',
                        name: 'total_fee'
                    },
                    {
                        data: 'payment_received',
                        name: 'payment_received'
                    },
                    {
                        data: 'change',
                        name: 'change'
                    },
                ],
                drawCallback: function(settings) {
                    let api = this.api();
                    let total = 0;

                    // Ambil semua nilai dari kolom "Sub total" (indeks ke-3)
                    api.column(3, {
                        page: 'all'
                    }).data().each(function(value, index) {
                        // Pastikan value dalam bentuk angka
                        total += parseFloat(value) || 0;
                    });

                    // Format angka ke Rupiah dan masukkan ke elemen #total_sub_total
                    document.getElementById('total_sub_total').innerText = 'Pendapatan : Rp ' + total.toLocaleString(
                        'id-ID');
                }
            });
            $('#filter-button').click(function() {
                $('#historyTable').DataTable().ajax.reload();
            });
        });

        function printStrukUlang(button) {
            // Tampilkan loading
            // document.getElementById('loadingPrint').classList.remove('d-none');

            const idOrder = button.getAttribute('data-id-order');
            const noInvoice = button.getAttribute('data-id-invoice');
            const total = parseInt(button.getAttribute('data-id-total'));
            const dibayarkan = parseInt(button.getAttribute('data-id-paid'));
            const kembali = parseInt(button.getAttribute('data-id-change'));
            const metode = button.getAttribute('data-id-metode') || '-';

            // Ambil item struk dari backend
            fetch(`/shop-order/${idOrder}/items`)
                .then(res => res.json())
                .then(items => {
                    let strukItemsHTML = '';
                    items.forEach(item => {
                        strukItemsHTML += `
                    <div style="display: flex; justify-content: space-between;">
                        <span>${item.quantity}x ${item.product_name}</span>
                        <span>Rp ${item.price.toLocaleString('id-ID')}</span>
                    </div>
                `;
                    });

                    const strukHTML = `
                <center>
                    <h4>{{ env('APP_NAME') }} <br> {{ $shop->name }}</h4>
                    <p>{{ $shop->address }}</p>
                    <p>Kasir : {{ Auth::user()->name }}</p>
                    <p>${(new Date()).toLocaleString('id-ID')}</p>
                    <hr>
                </center>

                ${strukItemsHTML}

                <hr>
                <div style="display: flex; justify-content: space-between;">
                    <strong>Total:</strong>
                    <span>Rp ${total.toLocaleString('id-ID')}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Dibayar:</span>
                    <span>Rp ${dibayarkan.toLocaleString('id-ID')}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Kembalian:</span>
                    <span>Rp ${kembali.toLocaleString('id-ID')}</span>
                </div>
                <hr>
                <div style="display: flex; justify-content: space-between;">
                    <span>Metode:</span>
                    <span>${metode}</span>
                </div>
                <center>
                    <hr>
                    <p>Terima kasih!</p>
                </center>
            `;

                    const printWindow = window.open('', '_blank', 'width=400,height=600');
                    printWindow.document.write(`
                        <html>
                        <head>
                            <title>Struk ${noInvoice}</title>
                            <style>
                                body { font-family: monospace; font-size: 12px; padding: 10px; }
                                h4, p, div, span, hr { margin: 0; padding: 0; }
                                hr { margin: 5px 0; border-top: 1px dashed #000; }
                                .line { display: flex; justify-content: space-between; }
                            </style>
                        </head>
                        <body onload="window.print(); window.close();">
                            ${strukHTML}
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                })
                .catch(error => {
                    showCustomAlert("Gagal mengambil data struk.");
                    console.error(error);
                })
                .finally(() => {
                    document.getElementById('spanPrint').classList.remove('d-none');
                    document.getElementById('loadingPrint').classList.add('d-none');
                });
        }
    </script>
@endpush

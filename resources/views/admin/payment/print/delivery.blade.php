<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Delivery Invoice {{ $data->no_invoice }}</title>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <link rel="stylesheet" href="{{ asset('css/') }}/pdf/bootstrap.min.css" media="all" />
    <style>
        @page {
            size: 8.5in 5.5in portrait;
            margin: 0.6in;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        hr {
            margin: 1px;
            border: none;
            border-top: 2px solid #000;
        }

        .table-custom {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
        }

        .table-custom tr,
        .table-custom th,
        .table-custom td {
            padding-left: 5px;
            border: 1px solid black;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            .no-print {
                display: none;
            }

            .page-break {
                page-break-after: always;
            }
        }
    </style>
    <link href="{{ public_path('img/logo.png') }}" rel="icon" type="image/png">
</head>

<body>
    <main class="px-4">
        <div>
            <!-- Header -->
            <table style=" width:100%; margin-bottom:0px;">
                <tr>
                    <td style="width: 30%" class="text-left fw-bold">
                        <span><b style="font-size: 40px;">INVOICE </b><br><span
                                class="font-size:10px;">DELIVERY</span></span>
                    </td>
                    <td class="text-right px-4" style="width: 50%">
                        <b>
                            <p class="m-0" style="font-size: 24px;"><b>AVIANTARA GROUP</b></p>
                            <i style="font-size: 14px;">Jalan Husen Palela</i>
                        </b>
                    </td>
                    <td style="width: 20%">
                        <img style="width: 150px; padding:5px;" src="{{ asset('img/') }}/logo.png">
                    </td>
                </tr>
            </table>
            <hr class="mb-4">

            <!-- Info -->
            <table style="width:100%; border:0; font-size:16px;">
                <tr>
                    <td><b>KEPADA :</b></td>
                    <td class="text-right"><b>TANGGAL :</b></td>
                </tr>
                <tr>
                    <td>{{ $data->customer->name }}</td>
                    <td class="text-right">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td>{{ $data->customer->phone }}</td>
                    <td class="text-right"><b>NO INVOICE :</b></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right">#{{ $data->no_invoice }}</td>
                </tr>
            </table>
        </div>
        <br>

        <!-- Items Table -->
        <div>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>BARANG</th>
                        <th>QTY</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 0; @endphp
                    @foreach ($items as $item)
                        @if ($counter % 15 == 0 && $counter > 0)
                </tbody>
            </table>
            <div class="page-break"></div>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>BARANG</th>
                        <th>QTY</th>
                    </tr>
                </thead>
                <tbody>
                    @endif
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach
                    <tr>
                        <td colspan="2"><b>TOTAL BARANG</b></td>
                        <td><b>{{ $items->sum('quantity') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <br>
        <div>
            <table style="width:100%; border:0; font-size:16px;">
                <tr>
                    <td><b>PENGANTARAN :</b></td>
                    <td colspan="2" class="text-right">TOTAL : </td>
                    <td class="text-right"> <b>{{ $items->sum('quantity') }}</b> Barang</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-right">BIAYA : </td>
                    <td class="text-right">Rp {{ number_format($data->additional_fee) }}</td>
                </tr>
            </table>
        </div>
        <hr>

        <!-- Delivery Address -->
        <div style="font-size:14px;">
            <strong>ALAMAT PENGANTARAN : </strong>
            <p>{{ $data->address_delivery }}</p>
            <strong>KETERANGAN : </strong>
            <p>{{ $data->description }}</p>
        </div>

        <!-- Footer -->
        <table style="width:100%; border:0; font-size:12px;">
            <tr class="text-center">
                <td>PETUGAS : </td>
                <td>SUPIR : </td>
                <td>PENERIMA : </td>
            </tr>
            <tr>
                <td colspan="3" style="height: 30px;"></td>
            </tr>
            <tr class="text-center">
                <td><b>{{ Auth::user()->name }}</b></td>
                <td>................</td>
                <td>................</td>
            </tr>
        </table>
        <br>
        <small style="color: rgba(255, 81, 0, 0.734); font-size:10px;">
            <i>* Sebagai bukti terima Barang, harap untuk menandatangani.</i>
        </small>
    </main>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>

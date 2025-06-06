<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $data->no_invoice }}</title>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <link rel="stylesheet" href="{{ asset('css/') }}/pdf/bootstrap.min.css" media="all" />
    <style>
        @page {
            size: 8.5in 5.5in portrait;
            margin: 0.6in;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 18px;
        }

        hr {
            margin: 1px;
            border: none;
            border-top: 2px solid #000;
        }

        .table-custom {
            border-collapse: collapse;
            width: 100%;
            font-size: 18px;
        }

        .table-custom tr,
        .table-custom th,
        .table-custom td {
            padding: 4px;
            border: 1px solid black;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }

        .no-break {
            page-break-inside: avoid;
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
    <link href="{{ public_path('img/logo_black.png') }}" rel="icon" type="image/png">
</head>

<body>
    <main>
        <div>
            <!-- Header -->
            <table style="width:100%; margin-bottom:10px;">
                <tr>
                    <td style="width: 30%" class=" fw-bold">
                        <h1 style="font-size: 18px;"><b>INVOICE</b></h1>
                    </td>
                    <td class="text-right" style="width: 50%">
                        <b>
                            <p class="m-0" style="font-size: 18px;"><b>AVIANTARA GROUP</b></p>
                            <i style="font-size: 18px;">Jalan Husen Palela</i>
                        </b>
                    </td>
                    <td style="width: 20%">
                        <img style="width: 100px; padding:5px;" src="{{ asset('img/') }}/logo.png">
                    </td>
                </tr>
            </table>
            <hr>

            <!-- Info -->
            <table style="width:100%; border:0;">
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
                    <td style="color: red">
                        Jatuh tempo :
                        @if ($data->due_date != null)
                            {{ $data->due_date }}
                        @endif
                    </td>
                    <td class="text-right">#{{ $data->no_invoice }}</td>
                </tr>
            </table>
            <br>

            <!-- Items -->
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>BARANG</th>
                        <th>HARGA</th>
                        <th>QTY</th>
                        <th>DISKON</th>
                        <th>JUMLAH</th>
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
                        <th>NO.</th>
                        <th>BARANG</th>
                        <th>HARGA</th>
                        <th>QTY</th>
                        <th>DISKON</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @endif
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp {{ number_format($item->price) }}</td>
                        <td>{{ $item->quantity }} {{ $retail ? $item->product->sub_unit : $item->product->unit }}</td>
                        <td>
                            @if ($item->discount_persen > 0)
                                {{ $item->discount_persen }} %
                            @elseif ($item->discount_rupiah > 0)
                                Rp {{ number_format($item->discount_rupiah) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->subtotal) }}</td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <br>
        <div class="no-break">
            <table style="width:100%; border:0;">
                <tr>
                    <td><b>PEMBAYARAN :</b></td>
                    <td colspan="2" class="text-right">SUB TOTAL : </td>
                    <td class="text-right">Rp {{ number_format($data->fee) }}</td>
                </tr>
                @if ($data->discount > 0 || $data->discount_rupiah > 0)
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right">DISKON : </td>
                        @if ($data->discount > 0)
                            <td class="text-right">{{ $data->discount }} %</td>
                        @elseif($data->discount_rupiah > 0)
                            <td class="text-right">Rp {{ number_format($data->discount_rupiah) }}</td>
                        @endif
                    </tr>
                @endif
                @if ($data->delivery == 1)
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right">PENGANTARAN : </td>
                        <td class="text-right">Rp {{ $data->additional_fee }} </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2"></td>
                    <td class="text-right"><b>TOTAL : </b></td>
                    <td class="text-right">Rp {{ number_format($data->total_fee) }}</td>
                </tr>
            </table>
            <br>
            <table style="width:100%; border:0; font-size:118x;">
                <tr style="padding-bottom:100px;" class="text-center">
                    <td>PETUGAS : </td>
                    <td>
                        @if ($data->delivery == 1)
                            Supir
                        @endif
                    </td>
                    <td>PENERIMA : </td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 30px;"></td>
                </tr>
                <tr class="text-center">
                    <td><b>{{ Auth::user()->name }}</b></td>
                    <td>
                        @if ($data->delivery == 1)
                            ................
                        @endif
                    </td>
                    <td>................</td>
                </tr>
            </table>
        </div>
    </main>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>

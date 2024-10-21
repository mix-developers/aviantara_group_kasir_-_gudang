<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $data->no_invoice }}</title>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <link rel="stylesheet" href="{{ asset('css/') }}/pdf/bootstrap.min.css" media="all" />
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 20px;
        }

        hr {
            margin: 1px;

            border: none;
            border-top: 2px solid #000;
        }

        tr {
            margin: 0 !important;
            padding: 0 !important;
        }

        .table-custom {
            border-collapse: collapse;
            width: 100%;
        }

        .table-custom tr,
        .table-custom th,
        .table-custom td {
            padding-left: 5px;
            border: 1px solid black;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
    <link href="{{ public_path('img/logo.png') }}" rel="icon" type="image/png">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"> --}}
</head>

<body>
    <main class="px-4">
        <table style=" width:100%; margin-bottom:10px;">
            <tr>
                <td style="width: 30%" class="text-center fw-bold">
                    <h1 style="font-size: 80px;"><b>INVOICE</b></h1>
                </td>
                <td class="text-right px-4" style="width: 50%">
                    <b>
                        <p class="m-0" style="font-size: 24px;"><b>AVIANTARA GROUP</b></p>
                        <i style="font-size: 14px;">Jalan Husen palela</i>
                    </b>
                </td>
                <td style="width: 20%">
                    <img style="width: 100px;" src="{{ asset('img/') }}/logo.png">
                </td>
            </tr>
        </table>
        <hr class="mb-4">
        <table style="width:100%; border:0;">
            <tr>
                <td><b>KEPADA :</b></td>
                <td class="text-right"><b>TANGGAL :</b></td>
            </tr>
            <tr>
                <td>{{ $data->customer->name }}</td>
                <td class="text-right">{{ date('d F Y') }}</td>
            </tr>
            <tr>
                <td>{{ $data->customer->phone }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" class="text-right"><b>NO INVOICE :</b></td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">#{{ $data->no_invoice }}</td>
            </tr>
        </table>

        <br>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp 0</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <table style="width:100%; border:0;">
            <tr>
                <td><b>PEMBAYARAN :</b></td>
                <td colspan="2" class="text-right">SUB TOTAL : </td>
                <td class="text-right">Rp {{ $data->total_fee }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-right">PAJAK : </td>
                <td class="text-right">-</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-right"><b>TOTAL : </b></td>
                <td class="text-right">{{ $data->total_fee }}</td>
            </tr>

        </table>
    </main>
    <script>
        // Jalankan perintah print saat halaman selesai dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Delivery Invoice {{ $data->no_invoice }}</title>
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

        .table-custom tr {
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
                <td style="width: 30%" class="text-left fw-bold">
                    <h1 style="font-size: 80px;"><b>INVOICE</b></h1>
                    <small>PENGANTARAN BARANG</small>
                </td>
                <td class="text-right px-4" style="width: 50%">
                    <b>
                        <p class="m-0" style="font-size: 24px;"><b>AVIANTARA GROUP</b></p>
                        <i style="font-size: 14px;">Jalan Husen palela</i>
                    </b>
                </td>
                <td style="width: 20%">
                    <img style="width: 150px;" src="{{ asset('img/') }}/logo.png">
                </td>
            </tr>
        </table>
        <hr class="mb-4">
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
        <table class="table-custom">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PRODUK</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">TOTAL BARANG</td>
                    <td>{{ $items->sum('quantity') }}</td>
                </tr>
            </tbody>
        </table>
        <br>
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
        <hr>
        <br>
        <div style="font-size:14px;">
            <strong>CUSTOMER : </strong>
            <p>
                Nama: <b>{{ $data->customer->name }}</b><br>HP/WA : {{ $data->customer->phone }}
            </p>
            <strong>ALAMAT PENGANTARAN : </strong>
            <p>
                {{ $data->address_delivery }}
            </p>
            <strong>KETERANGAN : </strong>
            <p>
                {{ $data->description }}
            </p>
        </div>
        <br>
        <table style="width:100%; border:0; font-size:12px;">
            <tr style="padding-bottom:100px;" class="text-center">
                <td>KASIR : </td>
                <td>SUPIR : </td>
                <td>PENERIMA : </td>
            </tr>
            <tr>
                <td colspan="3" style="height: 30px;"></td>
            </tr>
            <tr class="text-center">
                <td>................</td>
                <td>................</td>
                <td>................</td>
            </tr>
        </table>
        <br>
        <small style="color: rgba(255, 81, 0, 0.734); font-size:10px;"><i>* Sebagai bukti terima Barang, harap untuk
                menandatangani.</i></small>
    </main>
    <script>
        // Jalankan perintah print saat halaman selesai dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>

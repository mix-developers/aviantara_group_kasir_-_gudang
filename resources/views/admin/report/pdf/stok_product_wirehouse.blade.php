<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Stok Gudang</title>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <link rel="stylesheet" href="{{ public_path('css') }}/pdf/bootstrap.min.css" media="all" />
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
    </style>
    <link href="{{ public_path('img/logo.png') }}" rel="icon" type="image/png">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"> --}}
</head>

<body>
    <main>
        <table style=" width:100%; margin-bottom:10px;">
            <tr>
                <td style="width: 20%" class="text-center">
                    <img style="width: 80px;" src="{{ public_path('img') }}/logo.png">
                </td>
                <td class="text-center" style="width: 80%">
                    <b>
                        <p class="m-0" style="font-size: 14px;"><b>AVIANTARA GROUP</b></p>
                        <i style="font-size: 10px;">Jalan Husen palela</i>
                    </b>
                </td>
                <td style="width: 20%"></td>
            </tr>
        </table>
        <hr>
        <p>
            <b>Laporan : </b> Stok Produk Gudang<br>
            <b>Periode : </b>
            {{ date('d-m-Y', strtotime($from_date)) . ' sampai ' . date('d-m-Y', strtotime($to_date)) }}<br>
            <b>Gudang : </b> {{ $wirehouse ?? 'Semua' }}<br>
        </p>
        <table class="table-custom">
            <thead style="background-color: rgb(224, 116, 0); color:white; " class="text-center">
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Gudang</th>
                    <th>Stok</th>
                    <th>Stok Retail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->wirehouse->name }}</td>
                        <td>{{ App\Models\Product::getStok($item->id) }}</td>
                        <td>{{ App\Models\OrderWirehouseRetailItem::where('id_product', $item->id)->sum('quantity') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>

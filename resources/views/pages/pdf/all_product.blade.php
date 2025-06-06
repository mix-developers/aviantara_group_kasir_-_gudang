<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>HARGA PRODUK AVIANTARA</title>
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
        <div class="text-center">

            <h4 style="font-family: Arial, sans-serif;">DAFTAR HARGA PRODUK</h4>
            <p><span class="text-danger">Berlaku mulai bulan {{ date('F Y') }}</span><br><small>*Harga sewaktu-waktu
                    dapat
                    berubah</small></p>
        </div>
        <div class="row">
            @foreach (App\Models\Wirehouse::all() as $wirehouseItem)
                <table class="table-custom mb-3">
                    <thead style="color:white; " class="text-center">
                        <tr style="background-color: rgb(224, 116, 0); ">
                            <th colspan="4">{{ $wirehouseItem->name }}</th>
                        </tr>
                        <tr style="background-color: rgb(1, 177, 10); ">
                            <th style="width:10px;">NO</th>
                            <th>NAMA</th>
                            <th>ISI</th>
                            <th>HARGA GROSIR</th>
                            {{-- <th>HARGA SATUAN</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (App\Models\Product::with(['wirehouse'])->where('id_wirehouse', $wirehouseItem->id)->get() as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    {{ $item->quantity_unit }}
                                    {{ $item->sub_unit }}</td>
                                <td>
                                    {{ App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()? 'Rp ' .number_format(App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()->price_grosir): 'Belum diberi harga' }}
                                </td>
                                {{-- <td>
                                    {{ App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()? 'Rp ' .number_format(App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()->price_grosir / $item->quantity_unit): 'Belum diberi harga' }}
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="" style="page-break-after: auto;">

                </div>
            @endforeach
        </div>
    </main>
</body>

</html>

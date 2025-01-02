<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Opname Gudang</title>
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

        .page-break {
            page-break-after: always;
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
            <b>Laporan : </b>Opname Gudang<br>
            <b>Bulan : </b>{{ Carbon\Carbon::createFromFormat('m', $month)->translatedFormat('F') }}
            {{ $year }}<br>
            <b>Gudang : </b>{{ $wirehouse->name }}<br>
        </p>
        <table class="table-custom">
            <thead>
                <tr style="background-color: rgb(224, 116, 0); color:white;" class="text-center">
                    <td colspan="3">Ringkasan</td>
                </tr>
                <tr>
                    <td>Total Produk</td>
                    <td colspan="2"> : {{ $data->count() }} Produk</td>
                </tr>
                <tr>
                    <td>Total Stok Grosir</td>
                    <td colspan="2"> : {{ number_format(App\Models\ProductStok::getAllStok($month, $year)) ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td>Total opname</td>
                    <td class="bg-primary text-white">Sudah : {{ $opname_item->count() }} Produk</td>
                    <td class="bg-danger text-white">Belum : {{ $data->count() - $opname_item->count() }} Produk</td>
                </tr>
                <tr>
                    <td>Stok Grosir ter-opname</td>
                    <td><b>Sistem :</b><br> Grosir : {{ $opname_item->sum('qty') }}<br>
                        Retail :
                        {{ $opname_item->sum('qty_retail') }}

                    </td>
                    <td><b>Asli :</b><br>
                        Grosir : {{ $opname_item->sum('qty_real') }} <br>
                        Retail : {{ $opname_item->sum('qty_real_retail') }}
                    </td>
                </tr>
                <tr>
                    <td>Selisih Stok</td>
                    <td class="bg-danger text-white"><b>Hilang :</b>
                        <br>
                        Grosir :
                        {{ $opname_item->where('selisih', '<', 0)->count() }}
                        <br>
                        Retail :
                        {{ $opname_item->where('selisih_retail', '<', 0)->count() }}
                    </td>
                    <td> <b>Lebih :</b><br>
                        Grosir :
                        {{ $data->where('selisih', '>', 0)->count() }} <br>
                        Retail :
                        {{ $data->where('selisih_retail', '>', 0)->count() }}
                    </td>
                </tr>
                <tr>
                    <td>Perkiraan Pendapatan</td>
                    <td>
                        <b>Grosir :</b>
                        Rp
                        {{ number_format(App\Models\Product::estimateIncomeWirehouse($wirehouse->id, $month, $year)) }}
                    </td>
                    <td>
                        <b>Retail : </b> Rp
                        {{ number_format(App\Models\Product::estimateIncomeRetailWirehouse($wirehouse->id, $month, $year)) }}
                    </td>
                </tr>
                <tr>
                    <td>Perkiraan Kerugian</td>
                    <td colspan="2" class="text-danger">Rp 0</td>
                </tr>

                <tr>
                    <td>Update awal</td>
                    <td colspan="2">
                        @php
                            $opnameItem = App\Models\OpnameItem::where('id_wirehouse', $wirehouse->id)
                                ->where('month', $month)
                                ->where('year', $year)
                                ->first();
                        @endphp

                        {{ optional($opnameItem)->created_at ? $opnameItem->created_at->format('d F Y, H:i') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td>Update terakhir</td>
                    <td colspan="2">
                        @php
                            $opnameItem = App\Models\OpnameItem::where('id_wirehouse', $wirehouse->id)
                                ->where('month', $month)
                                ->where('year', $year)
                                ->latest()
                                ->first();
                        @endphp

                        {{ optional($opnameItem)->updated_at ? $opnameItem->updated_at->format('d F Y, H:i') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td>di cetak oleh</td>
                    <td colspan="2">{{ Auth::user()->name }}
                    </td>
                </tr>
                <tr>
                    <td>di cetak pada</td>
                    <td colspan="2">{{ date('d F Y, H:i') }}
                    </td>
                </tr>


            </thead>
        </table>
        <br>

        <div class="page-break"></div>
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
        <h4 class="text-center" style=" font-family: Arial, sans-serif;">RINCIAN OPNAME</h4>
        <table class="table-custom">
            <thead style="background-color: rgb(224, 116, 0); color: white;" class="text-center">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Produk</th>
                    <th colspan="2">Stok Sistem</th>
                    <th colspan="2">Stok Asli</th>
                    <th colspan="2">Selisih</th>
                    <th rowspan="2">Keterangan</th>
                    <th rowspan="2">Staff</th>
                </tr>
                <tr>
                    <th>Grosir</th>
                    <th>Retail</th>
                    <th>Grosir</th>
                    <th>Retail</th>
                    <th>Grosir</th>
                    <th>Retail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ App\Models\OpnameItem::Qty($month, $year, $item->id) }}</td>
                        <td>{{ App\Models\OpnameItem::QtyRetail($month, $year, $item->id) }}</td>
                        <!-- Retail untuk Stok Sistem diisi 0 -->
                        <td>{{ App\Models\OpnameItem::QtyReal($month, $year, $item->id) }}</td>
                        <td>{{ App\Models\OpnameItem::QtyRealRetail($month, $year, $item->id) }}</td>
                        <!-- Retail untuk Stok Asli diisi 0 -->
                        <td>{{ App\Models\OpnameItem::selisih($month, $year, $item->id) }}</td>
                        <td>{{ App\Models\OpnameItem::selisihRetail($month, $year, $item->id) }}</td>
                        <!-- Retail untuk Stok Asli diisi 0 -->
                        <td>{{ App\Models\OpnameItem::description($month, $year, $item->id) }}</td>
                        <td>{{ App\Models\OpnameItem::staff($month, $year, $item->id) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </main>

</body>

</html>

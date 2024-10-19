<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $data->no_invoice }}</title>
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
        <div class="text-center my-3">
            <h2>Invoice Penjualan</h2>
        </div>
        <table class="table-borderless w-100">
            <tr>
                <td>Nama Perusahaan</td>
                <td>:</td>
                <td style="width:200px;">Aviantara Group</td>
                <td>No. Invoice</td>
                <td>:</td>
                <td>{{ $data->no_invoice ?? '' }}</td>
            </tr>
            <tr>
                <td>Customer</td>
                <td>:</td>
                <td style="width:200px;">
                    <b>{{ $data->customer->name ?? '' }}</b><br><small>{{ $data->cutomer->phone ?? '' }}</small>
                </td>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $data->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td style="width:200px;" colspan="4">{{ $data->customer->address_hom ?? '' }}</td>

            </tr>
            <tr>
                <td>Alamat Usaha</td>
                <td>:</td>
                <td style="width:200px;" colspan="4">{{ $data->customer->address_company ?? '' }}</td>
            </tr>
        </table>
    </main>
</body>

</html>

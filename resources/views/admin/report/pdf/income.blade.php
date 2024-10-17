<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>
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
            <b>Laporan : </b> Pendapatan<br>
            <b>Metode : </b> {{ $metode }}<br>
            <b>Periode : </b>
            {{ date('d-m-Y', strtotime($from_date)) . ' sampai ' . date('d-m-Y', strtotime($to_date)) }}
        </p>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Metode</th>
                    <th>Dibayarkan</th>
                    <th>Deskripsi</th>
                    <th>Pegawai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>{{ $item->payment_method->method }}</td>
                        <td> Rp {{ number_format($item->paid) }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->user->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </main>

</body>

</html>

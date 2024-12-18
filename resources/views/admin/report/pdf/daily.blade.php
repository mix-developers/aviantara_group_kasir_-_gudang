<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Harian</title>
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
            <b>Laporan Harian: </b>{{ App\Models\Wirehouse::find(Auth::user()->id_wirehouse)->name }}<br>
            <b>Tanggal : </b>{{ date('d F Y') }}<br>
            <b>Jenis : </b>Pembayaran<br>
            <hr>

            @php
                $groupedData = $data->groupBy('payment_method.id'); // Kelompokkan berdasarkan metode pembayaran
                $grandTotal = 0;
            @endphp

        <table class="table-custom">
            <thead style="background-color: rgb(224, 116, 0); color:white;" class="text-center">
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
                @php
                    $rowNumber = 1; // Untuk menjaga penomoran di seluruh tabel
                @endphp

                @foreach ($groupedData as $paymentMethodId => $items)
                    {{-- Header per Metode Pembayaran --}}
                    <tr>
                        <td colspan="6" style="background-color: #f0f0f0; font-weight: bold;">
                            Metode Pembayaran: {{ $items->first()->payment_method->method }}
                        </td>
                    </tr>

                    {{-- Data untuk tiap item --}}
                    @php
                        $totalPaid = 0;
                    @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $rowNumber++ }}</td>
                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                            <td>{{ $item->payment_method->method }}</td>
                            <td>Rp {{ number_format($item->paid) }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->user->name }}</td>
                        </tr>
                        @php
                            $totalPaid += $item->paid;
                            $grandTotal += $item->paid;
                        @endphp
                    @endforeach

                    {{-- Total per Metode Pembayaran --}}
                    <tr style="background-color: rgb(224, 116, 0); color:white;">
                        <td colspan="3" style="text-align: right;">TOTAL
                            {{ $items->first()->payment_method->method }}</td>
                        <td colspan="3"><b>Rp {{ number_format($totalPaid) }}</b></td>
                    </tr>
                @endforeach

                {{-- Grand Total --}}
                <tr style="background-color: black; color:white; text-size:24px;">
                    <td colspan="3" style="text-align: right;">GRAND TOTAL</td>
                    <td colspan="3"><b>Rp {{ number_format($grandTotal) }}</b></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%;">
            @php
                $groupedData = $data->groupBy('id_user'); // Kelompokkan data berdasarkan user_id
            @endphp
            <tr>
                @foreach ($groupedData as $userId => $items)
                    <td colspan="6" style="text-align: center; padding-top: 20px;">
                        <strong>{{ $items->first()->user->name }}</strong>
                        <br>
                        <br>
                        <br>
                        <br>
                        <small style="font-style: italic;">(....................................)</small>
                    </td>
                @endforeach
            </tr>
        </table>
        {{-- page 2 --}}
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
        <p>
            <b>Laporan Harian: </b>{{ App\Models\Wirehouse::find(Auth::user()->id_wirehouse)->name }}<br>
            <b>Tanggal : </b>{{ date('d F Y') }}<br>
            <b>Jenis : </b>Piutang<br>
        </p>
        <table class="table-custom">
            <thead style="background-color: rgb(224, 116, 0); color:white;" class="text-center">
                <tr>
                    <th>No</th>
                    <th>Tgl Transaksi</th>
                    <th>Jatuh Tempo</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Sisa Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @php

                    $order_wirehouses = App\Models\OrderWirehouse::where(
                        'id_wirehouse',
                        Auth::user()->id_wirehouse,
                    )->get();
                    $totalRemainingBalance = 0;
                @endphp
                @foreach ($order_wirehouses as $OrderWirehouse)
                    @php

                        $check_payment = App\Models\OrderWirehousePayment::where(
                            'id_order_wirehouse',
                            $OrderWirehouse->id,
                        )->sum('paid');
                        $total_fee = $OrderWirehouse->total_fee;

                        if ($check_payment < $total_fee) {
                            $remaining_balance = $total_fee - $check_payment;
                        } else {
                            $remaining_balance = 0;
                        }
                        $totalRemainingBalance += $remaining_balance;
                    @endphp
                    @if ($remaining_balance > 0)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $OrderWirehouse->created_at->format('d F Y') }}</td>
                            <td style="color: red;">{{ $OrderWirehouse->due_date }}</td>
                            <td>{{ $OrderWirehouse->no_invoice }}</td>
                            <td><b>{{ $OrderWirehouse->customer->name }}</b><br>{{ $OrderWirehouse->customer->phone }}
                            </td>
                            <td>Rp {{ number_format($remaining_balance) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr style="background-color: black; color:white; text-size:24px;">
                    <td colspan="5" style="text-align: center;">GRAND TOTAL</td>
                    <td><b>Rp {{ number_format($totalRemainingBalance) }}</b></td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%;">
            @php
                $groupedData = $data->groupBy('id_user'); // Kelompokkan data berdasarkan user_id
            @endphp
            <tr>
                @foreach ($groupedData as $userId => $items)
                    <td colspan="6" style="text-align: center; padding-top: 20px;">
                        <strong>{{ $items->first()->user->name }}</strong>
                        <br>
                        <br>
                        <br>
                        <br>
                        <small style="font-style: italic;">(....................................)</small>
                    </td>
                @endforeach
            </tr>
        </table>

    </main>

</body>

</html>

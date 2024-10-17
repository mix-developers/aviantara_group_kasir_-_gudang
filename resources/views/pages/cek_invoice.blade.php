@extends('layouts.auth.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            {{-- <div class="text-center">
                <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 20vh;" class="text-center">
            </div> --}}
            <div class="app-brand justify-content-center " style="line-height:2; margin-bottom:10px;">
                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                    </span>
                    <span class="app-brand-text demo text-body "><span class="fw-bolder text-warning">AVIANTARA</span>
                        <small style="font-size: 20px;"> GROUP</small></span>
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Cek Invoice</h4>
            <p class="mb-4">Silahkan isi nomor invoice di bawah terlebih dahulu</p>
            <form id="formInvoice">
                <div class="input-group mb-3 input-group-merge">
                    <input type="search" class="form-control" id="no_invoice" name="no_invoice" placeholder="Nomor Invoice"
                        autofocus />
                    <button type="button" class="btn btn-outline-primary" id="btn-search"><i
                            class="bx bx-search"></i></button>
                </div>
            </form>
            <hr>
            @guest
                <p class="text-center">
                    <span>Ingin melakukan login ?</span>
                    <a href="{{ route('login') }}">
                        <span>Klik di sini</span>
                    </a>
                </p>
            @else
                <p class="text-center">
                    <span>Ingin kembali ke dashboard?</span>
                    <a href="{{ url('/') }}">
                        <span>Klik di sini</span>
                    </a>
                </p>
            @endguest
        </div>
    </div>
    <center>
        <div class="spinner-border spinner-border-lg text-primary " role="status"
            style="margin-top:50px;margin-bottom:50px; display:none;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </center>


    <div class="card mt-4" id="cardInvoice" style="max-width:900px; display:none;">
        <div class="card-body">
            <span id="span-info"></span>
            <table class="table table-bordered table-hover table-sm" id="table-invoice">
                <thead>
                    <tr>
                        <th colspan='2' style="text-align:center;" class="fw-bold text-primary">Invoice</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="card mt-4" id="cardDelivery" style="max-width:900px; display:none;">
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm" id="table-delivery">
                <thead>
                    <tr>
                        <th colspan='2' style="text-align:center;" class="fw-bold text-primary">pengantaran</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="card mt-4" id="cardItems" style="max-width:900px; display:none;">
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm" id="table-items">
                <thead>
                    <tr>
                        <th colspan='3' style="text-align:center;" class="fw-bold text-primary">Data pembelian</th>
                    </tr>
                    <tr>
                        <th style="text-align:center;">Produk</th>
                        <th style="text-align:center;">Jumlah</th>
                        <th style="text-align:center;">Subtotal</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#btn-search').click(function() {

            var no = $('#no_invoice').val();
            /**
             * get invoice 
             */
            $.ajax({
                type: 'GET',
                url: '/get-invoice/' + no,
                success: function(data) {

                    // console.log(data);
                    if (data.message) {
                        alert(data.message);
                    } else {

                        $('#cardInvoice').show();
                        $('#layout-auth').css({
                            "max-width": "600px"
                        });
                        var items_fee = data.total_fee -
                            data.additional_fee;
                        items = [];
                        deliverys = [];

                        $.ajax({
                            type: 'GET',
                            url: '/get-order-wirehouse-items/' + data.id,
                            success: function(respons) {
                                console.log(respons.payment);
                                console.log(data.total_fee);
                                var color;
                                var status;
                                var deskripsi;
                                var sisa = data.total_fee - respons.payment;
                                var sisa_text;
                                var sisa = data.total_fee - respons.payment;
                                if (respons.payment <= 0) {
                                    color = 'danger';
                                    status = 'MENUNGGU PEMBAYARAN';
                                    deskripsi =
                                        'Segera lakukan pembayaran pada tagihan anda...!';
                                    sisa_text = '';
                                } else if (respons.payment == data.total_fee) {
                                    color = 'primary';
                                    status = 'TAGIHAN LUNAS';
                                    deskripsi = 'Terimakasih atas kepercayaan anda...';
                                    sisa_text = '';
                                } else {
                                    color = 'warning';
                                    status = 'PROSES PENCICILAN';
                                    deskripsi =
                                        'Segera lakukan pelunasan pada tagihan anda...!';
                                    sisa_text = 'Sisa tagihan sebesar <strong> Rp ' +
                                        formatNumberWithDot(sisa) + '</strong>';
                                }
                                $('#span-info').html(`
                                <div class="alert alert-` + color + `">
                                    <h6 class="alert-heading fw-bold mb-1">` + status + `</h6>
                                    <p class="mb-0">` + deskripsi + `<br>` + sisa_text + `</p>
                                    </div>
                                `);
                            },
                        });

                        items.push("<tr><td class='fw-bold'>No. Invoice</td><td>" + data
                            .no_invoice +
                            "</td></tr>");
                        items.push("<tr><td class='fw-bold'>Pelanggan</td><td>" +
                            data.customer.name +
                            "</td></tr>");
                        items.push("<tr><td class='fw-bold'>No HP/WA</td><td>" +
                            data.customer.phone +
                            "</td></tr>");
                        items.push(
                            "<tr><td class='fw-bold'>Alamat Rumah</td><td>" + data.customer
                            .address_home +
                            "</td></tr>");
                        items.push(
                            "<tr><td class='fw-bold'>Alamat Usaha</td><td>" + data.customer
                            .address_company +
                            "</td></tr>");
                        items.push(
                            "<tr><td style='text-align:center;' colspan='2' class='text-warning'>TAGIHAN</td></tr>"
                        );
                        items.push("<tr><td >Total Pembelian</td><td> Rp " + formatNumberWithDot(
                                items_fee) +
                            "</td></tr>");
                        if (data.delivery == 1) {
                            items.push("<tr><td >Biaya Tambahan</td><td> Rp " +
                                formatNumberWithDot(data.additional_fee) +
                                "</td></tr>");
                        }
                        items.push(
                            "<tr><td class='fw-bold'>Total Pembayaran </td><td class='fw-bold text-danger'> Rp " +
                            formatNumberWithDot(data.total_fee) +
                            "</td></tr>");

                        if (data.delivery == 1) {
                            $('#cardDelivery').show();
                            deliverys.push(
                                "<tr><td class='fw-bold'>Biaya Tambahan </td><td class='text-danger fw-bold'> Rp " +
                                formatNumberWithDot(data
                                    .additional_fee) +
                                "</td></tr>");
                            deliverys.push("<tr><td class='fw-bold'>Alamat Pengantaran</td><td>" +
                                data
                                .address_delivery +
                                "</td></tr>");
                        }

                        $('#table-delivery tbody').html('').append(deliverys);
                        $(
                            '#table-invoice tbody').html('').append(items);

                        //order items
                        $.ajax({
                            type: 'GET',
                            url: '/get-order-wirehouse-items/' + data.id,
                            success: function(data) {
                                $('#cardItems').show();
                                orderItems = [];
                                $.each(data.items, function(key, val) {
                                    orderItems.push(
                                        "<tr><td>" +
                                        val
                                        .product.name +
                                        "</td><td>" +
                                        val
                                        .quantity + " " +
                                        val
                                        .product.unit +
                                        "</td><td> Rp " +
                                        formatNumberWithDot(val
                                            .subtotal) +
                                        "</td></tr>");
                                });
                                orderItems.push(
                                    "<tr><td colspan='2'>TOTAL</td><td class='text-danger fw-bold'> Rp" +
                                    formatNumberWithDot(data.total) +
                                    "</td><tr>"
                                );
                                $('#table-items tbody').html('').append(orderItems);
                            },
                            error: function(xhr) {
                                alert('Terjadi kesalahan: ' + xhr.responseText);
                            }
                        });
                    }

                },
                error: function(xhr) {
                    if ($('#no_invoice').val('')) {
                        alert('Harap mengisi nomor invoice terlebih dahulu');
                    } else {

                    }
                }
            });
        });

        function formatNumberWithDot(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>
@endpush

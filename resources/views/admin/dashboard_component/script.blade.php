@push('js')
    <script>
        //stok gudang
        $(function() {
            $('.refresh-stok').click(function() {
                getStok();

            });

            function getStok() {
                $.ajax({
                    type: 'GET',
                    url: '/get-stok-card',
                    success: function(response) {
                        $('#stokInput').text(response.stok_input);
                        $('#stokOut').text(response.stok_out);
                        $('#stokExpired').text(response.stok_damaged);
                        $('#stokNotExpired').text(response.stok_not_expired);
                        $('#stokWirehouse').text(response.stok_wirehouse);
                        $('#priceStokInput').text(formatNumberWithDot(response.price_stok_input));
                    }
                });
            };

            function expiredAlert() {
                $.ajax({
                    type: 'GET',
                    url: '/expired-alert',
                    success: function(response) {
                        var expiredText = '<span class="h4 text-danger"><i class="bx bx-error"></i> ' +
                            response.expired +
                            ' Barang pada gudang telah kadaluarsa ..</span>';
                        var remainingText = '<strong><i class="bx bx-error-circle"></i> ' + response
                            .remaining +
                            ' Barang pada gudang akan kadaluarsa..</strong><br><small>*Waktu peringatan dihitung 3 bulan sebelum tanggal kadaluarsa tiba.</small>';
                        if (response.expired != 0) {
                            getAlert(expiredText, 'danger');
                        }
                        if (response.remaining != 0) {
                            getAlert(remainingText, 'warning');

                        }
                    }
                });
            };
            @if (Auth::user()->role == 'Admin')
                function notPriceAlert() {
                    $.ajax({
                        type: 'GET',
                        url: '/prices/get-not-price',
                        success: function(response) {
                            var text = '<strong><i class="bx bx-error-circle"></i> ' + response +
                                ' Produk belum diberikan harga</small>';
                            if (response !== 0) {
                                getAlert(text, 'danger');

                            }
                        }
                    });
                };
            @endif

            function getAlert(alertValue, type) {
                $('#alert').append(
                    '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }

            function formatNumberWithDot(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Owner')
                notPriceAlert();
            @endif
            getStok();
            expiredAlert();
        });
    </script>
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Owner')
        {{-- grafik admin --}}
        <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.stock.min.js"></script>
        <script type="text/javascript">
            window.onload = function() {
                var dataPoints1 = [],
                    dataPoints2 = [];
                var stockChart = new CanvasJS.StockChart("chartContainer", {
                    theme: "light2",
                    animationEnabled: true,
                    title: {
                        text: "Grafik Transaksi"
                    },

                    charts: [{
                        axisY: {
                            title: "Transaksi"
                        },
                        toolTip: {
                            shared: true
                        },
                        legend: {
                            cursor: "pointer",
                            itemclick: function(e) {
                                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries
                                    .visible)
                                    e.dataSeries.visible = false;
                                else
                                    e.dataSeries.visible = true;
                                e.chart.render();
                            }
                        },
                        data: [{
                            showInLegend: true,
                            name: "No of Trades in $",
                            yValueFormatString: "#,##0",
                            xValueType: "dateTime",
                            dataPoints: dataPoints1
                        }, {
                            showInLegend: true,
                            name: "No of Trades in €",
                            yValueFormatString: "#,##0",
                            dataPoints: dataPoints2
                        }]
                    }],
                    rangeSelector: {
                        enabled: true
                    },
                    navigator: {
                        data: [{
                            dataPoints: dataPoints1
                        }],
                        slider: {
                            minimum: new Date(2018, 00, 15),
                            maximum: new Date(2018, 02, 01)
                        }
                    }
                });
                $.getJSON("https://canvasjs.com/data/docs/btcvolume2018.json", function(data) {
                    for (var i = 0; i < data.length; i++) {
                        dataPoints1.push({
                            x: new Date(data[i].date),
                            y: Number(data[i].volume_btc_usd)
                        });
                        dataPoints2.push({
                            x: new Date(data[i].date),
                            y: Number(data[i].volume_btc_eur)
                        });
                    }
                    stockChart.render();
                });
                // Chart for Pembayaran Customer
                var chartPaid = new CanvasJS.Chart("chartContainerLunas", {
                    theme: "light2",
                    animationEnabled: true,
                    title: {
                        text: "Pembayaran Customer"
                    },
                    data: [{
                        type: "pie",
                        indexLabelFontSize: 18,
                        radius: 80,
                        indexLabel: "{label} - {y}",
                        yValueFormatString: "###0.0\"%\"",
                        click: explodePie,
                        dataPoints: [{
                                y: 9,
                                label: "Lunas"
                            },
                            {
                                y: 3.1,
                                label: "Belum Lunas"
                            }
                        ]
                    }]
                });
                chartPaid.render();

                // Chart for Barang Kadaluarsa
                var chartExpire = new CanvasJS.Chart("chartContainerExpire", {
                    theme: "light2",
                    animationEnabled: true,
                    title: {
                        text: "Barang Kadaluarsa"
                    },
                    data: [{
                        type: "pie",
                        indexLabelFontSize: 18,
                        radius: 80,
                        indexLabel: "{label} - {y}",
                        yValueFormatString: "###0.0\"%\"",
                        click: explodePie,
                        dataPoints: [{
                                y: 42,
                                label: "Gas"
                            },
                            {
                                y: 21,
                                label: "Nuclear"
                            },
                            {
                                y: 24.5,
                                label: "Renewable"
                            },
                            {
                                y: 9,
                                label: "Coal"
                            },
                            {
                                y: 3.1,
                                label: "Other Fuels"
                            }
                        ]
                    }]
                });
                chartExpire.render();

                function explodePie(e) {
                    for (var i = 0; i < e.dataSeries.dataPoints.length; i++) {
                        if (i !== e.dataPointIndex)
                            e.dataSeries.dataPoints[i].exploded = false;
                    }
                }
            }
        </script>
    @endif
@endpush

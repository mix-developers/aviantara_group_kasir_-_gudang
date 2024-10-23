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
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.stock.min.js"></script>

    {{-- grafik admin --}}
    <script type="text/javascript">
        window.onload = function() {
            // Chart for Transaksi
            var stockChart = new CanvasJS.StockChart("chartContainer", {
                theme: "light2",
                animationEnabled: true,
                title: {
                    text: "Grafik Transaksi {{ Auth::user()->role == 'Gudang' ? 'Gudang' : '' }}"
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
                    data: [] // Empty array, will be filled dynamically
                }],
                rangeSelector: {
                    enabled: true
                },
                navigator: {
                    slider: {
                        minimum: new Date(2018, 00, 15), // Adjust to your date range
                        maximum: new Date(2018, 02, 01) // Adjust to your date range
                    }
                }
            });

            // Fetch data for Transaksi chart
            $.getJSON("/chart-order-all-wirehouse", function(data) {
                var datasets = [];

                // Loop through each dataset in the response (each warehouse)
                for (var i = 0; i < data.length; i++) {
                    datasets.push({
                        showInLegend: true,
                        name: data[i].label, // Use warehouse label from backend
                        yValueFormatString: "#,##0",
                        xValueType: "dateTime",
                        dataPoints: data[i].dataPoints // Use data points for each warehouse
                    });
                }

                // Add the datasets to the chart's data property
                stockChart.options.charts[0].data = datasets;

                // Render the chart after data is loaded
                stockChart.render();
            });

            // Chart for Pembayaran
            var stockChart2 = new CanvasJS.StockChart("chartContainer2", {
                theme: "light2",
                animationEnabled: true,
                title: {
                    text: "Grafik Pembayaran {{ Auth::user()->role == 'Gudang' ? 'Gudang' : '' }}"
                },
                charts: [{
                    axisY: {
                        title: "Pembayaran"
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
                    data: [] // Empty array, will be filled dynamically
                }],
                rangeSelector: {
                    enabled: true
                },
                navigator: {
                    slider: {
                        minimum: new Date(2018, 00, 15), // Adjust to your date range
                        maximum: new Date(2018, 02, 01) // Adjust to your date range
                    }
                }
            });

            // Fetch data for Pembayaran chart
            $.getJSON("/chart-payment-all-wirehouse", function(data) {
                var datasets2 = [];

                // Loop through each dataset in the response (each warehouse)
                for (var i = 0; i < data.length; i++) {
                    datasets2.push({
                        showInLegend: true,
                        name: data[i].label, // Use warehouse label from backend
                        yValueFormatString: "Rp #,##0",
                        xValueType: "dateTime",
                        dataPoints: data[i].dataPoints // Use data points for each warehouse
                    });
                }

                // Add the datasets to the chart's data property
                stockChart2.options.charts[0].data = datasets2;

                // Render the chart after data is loaded
                stockChart2.render();
            });
            // Chart for Pembayaran Customer
            // Function to fetch and render both charts
            function fetchAndRenderCharts() {
                fetch('/chart-paid')
                    .then(response => response.json())
                    .then(data => {
                        const dataPointsPaid = data.labels.map((label, index) => ({
                            y: data.datasets[0].data[index],
                            label: label,
                            color: data.datasets[0].backgroundColor[index]
                        }));

                        const chartPaid = new CanvasJS.Chart("chartContainerLunas", {
                            theme: "light2",
                            animationEnabled: true,
                            title: {
                                text: "Pembayaran"
                            },
                            data: [{
                                type: "pie",
                                indexLabelFontSize: 18,
                                radius: 80,
                                indexLabel: "{label} - {y}",
                                yValueFormatString: "###0",
                                click: explodePie,
                                dataPoints: dataPointsPaid
                            }]
                        });

                        chartPaid.render();
                    })
                    .catch(error => console.error('Error fetching data for chart paid:', error));

                fetch('/chart-expired')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data from /chart-expired:', data);
                        const dataPointsExpired = data.labels.map((label, index) => ({
                            y: data.datasets[0].data[index],
                            label: label,
                            color: data.datasets[0].backgroundColor[index]
                        }));

                        if (dataPointsExpired.every(point => point.y === 0)) {
                            document.getElementById("chartContainerExpire").innerHTML =
                                "No data available.";
                            return;
                        }

                        const chartExpired = new CanvasJS.Chart("chartContainerExpire", {
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
                                yValueFormatString: "###0",
                                click: explodePie,
                                dataPoints: dataPointsExpired
                            }]
                        });

                        chartExpired.render();
                    })
                    .catch(error => console.error('Error fetching data for chart expired:', error));
            }

            // Call the function to fetch data and render both charts
            fetchAndRenderCharts();

            function explodePie(e) {
                for (var i = 0; i < e.dataSeries.dataPoints.length; i++) {
                    if (i !== e.dataPointIndex)
                        e.dataSeries.dataPoints[i].exploded = false;
                }
            }
        }
    </script>
@endpush

@extends('layouts.backend.admin')

@section('content')
    <div class="text-center my-4">
        <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 30vh;"><br>
        <span class="text-warning h1">AVIANTARA</span> <span class="h4">GROUP</span>
        <hr>
    </div>
    <div class="my-3 text-center">
        <h4>Selamat datang kembali di <span class="text-primary">Sistem Informasi Manajemen Gudang dan
                Kios</span>
        </h4>
        @if (Auth::user()->role == 'Kasir')
            <p class="badge bg-label-danger w-auto w-md-100"><i class="bx bx-error"></i> Harap selalu cek fisik barang
                kadaluarsa<br> pada toko</p>
        @endif
    </div>
    <div class="mt-2" id="alert"></div>
    <hr>
    <div class="my-4 card">
        <div class="card-body">
            <div id="chartContainer" style="height: 500px;"></div>
        </div>
    </div>
    <div class="my-4 card">
        <div class="card-body">
            <div id="chartContainer2" style="height: 500px;"></div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.stock.min.js"></script>
    <script type="text/javascript">
        window.onload = function() {
            // Chart for Transaksi
            var stockChart = new CanvasJS.StockChart("chartContainer", {
                theme: "light2",
                animationEnabled: true,
                title: {
                    text: "Grafik Transaksi {{ Auth::user()->role == 'Kasir' ? 'Toko' : '' }}"
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
            $.getJSON("/chart-order-all-shop", function(data) {
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
                    text: "Grafik Pembayaran {{ Auth::user()->role == 'Kasir' ? 'Toko' : '' }}"
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
            $.getJSON("/chart-payment-all-shop", function(data) {
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
            // function fetchAndRenderCharts() {
            //     fetch('/chart-paid')
            //         .then(response => response.json())
            //         .then(data => {
            //             const dataPointsPaid = data.labels.map((label, index) => ({
            //                 y: data.datasets[0].data[index],
            //                 label: label,
            //                 color: data.datasets[0].backgroundColor[index]
            //             }));

            //             const chartPaid = new CanvasJS.Chart("chartContainerLunas", {
            //                 theme: "light2",
            //                 animationEnabled: true,
            //                 title: {
            //                     text: "Pembayaran"
            //                 },
            //                 data: [{
            //                     type: "pie",
            //                     indexLabelFontSize: 18,
            //                     radius: 80,
            //                     indexLabel: "{label} - {y}",
            //                     yValueFormatString: "###0",
            //                     click: explodePie,
            //                     dataPoints: dataPointsPaid
            //                 }]
            //             });

            //             chartPaid.render();
            //         })
            //         .catch(error => console.error('Error fetching data for chart paid:', error));

            //     fetch('/chart-expired')
            //         .then(response => response.json())
            //         .then(data => {
            //             console.log('Data from /chart-expired:', data);
            //             const dataPointsExpired = data.labels.map((label, index) => ({
            //                 y: data.datasets[0].data[index],
            //                 label: label,
            //                 color: data.datasets[0].backgroundColor[index]
            //             }));

            //             if (dataPointsExpired.every(point => point.y === 0)) {
            //                 document.getElementById("chartContainerExpire").innerHTML =
            //                     "No data available.";
            //                 return;
            //             }

            //             const chartExpired = new CanvasJS.Chart("chartContainerExpire", {
            //                 theme: "light2",
            //                 animationEnabled: true,
            //                 title: {
            //                     text: "Barang Kadaluarsa"
            //                 },
            //                 data: [{
            //                     type: "pie",
            //                     indexLabelFontSize: 18,
            //                     radius: 80,
            //                     indexLabel: "{label} - {y}",
            //                     yValueFormatString: "###0",
            //                     click: explodePie,
            //                     dataPoints: dataPointsExpired
            //                 }]
            //             });

            //             chartExpired.render();
            //         })
            //         .catch(error => console.error('Error fetching data for chart expired:', error));
            // }

            // Call the function to fetch data and render both charts
            // fetchAndRenderCharts();

            function explodePie(e) {
                for (var i = 0; i < e.dataSeries.dataPoints.length; i++) {
                    if (i !== e.dataPointIndex)
                        e.dataSeries.dataPoints[i].exploded = false;
                }
            }
        }
    </script>
@endpush

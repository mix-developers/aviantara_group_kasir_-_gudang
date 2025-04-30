@push('js')
    <script>
        $(function() {
            var table = $('#datatable-expired').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('expired-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            $('.create-new').click(function() {
                $('#create').modal('show');
            });

            $('.refresh').click(function() {
                $('#datatable-expired').DataTable().ajax.reload();
            });
        });
    </script>

    {{-- Load Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('expired.chart') }}")
                .then(response => response.json())
                .then(data => {
                    console.log("Data dari API:", data);

                    // Gabungkan labels dan hilangkan duplikasi
                    const allLabels = [...new Set([...data.labels, ...data.upcoming_labels])];

                    // Buat objek untuk data expired dan upcoming agar sejajar dengan label unik
                    let stokExpiredMap = {};
                    let stokUpcomingMap = {};
                    let lossExpiredMap = {};
                    let lossUpcomingMap = {};

                    // Isi data expired
                    data.labels.forEach((label, index) => {
                        stokExpiredMap[label] = data.data[index];
                        lossExpiredMap[label] = data.loss[index];
                    });

                    // Isi data upcoming
                    data.upcoming_labels.forEach((label, index) => {
                        stokUpcomingMap[label] = data.upcoming_data[index];
                        lossUpcomingMap[label] = data.upcoming_loss[index];
                    });

                    // Konversi ke array sejajar dengan allLabels
                    let stokExpiredData = allLabels.map(label => stokExpiredMap[label] || 0);
                    let stokUpcomingData = allLabels.map(label => stokUpcomingMap[label] || 0);
                    let lossExpiredData = allLabels.map(label => lossExpiredMap[label] || 0);
                    let lossUpcomingData = allLabels.map(label => lossUpcomingMap[label] || 0);

                    // =========================
                    // GRAFIK STOK KADALUARSA
                    // =========================
                    const ctx1 = document.getElementById('expiredStockChart').getContext('2d');
                    new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: allLabels,
                            datasets: [{
                                    label: 'Stok Kadaluarsa',
                                    data: stokExpiredData,
                                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Stok Akan Kadaluarsa',
                                    data: stokUpcomingData,
                                    backgroundColor: 'rgba(255, 205, 86, 0.6)',
                                    borderColor: 'rgba(255, 205, 86, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // =========================
                    // GRAFIK KERUGIAN KADALUARSA
                    // =========================
                    const ctx2 = document.getElementById('expiredLossChart').getContext('2d');
                    new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: allLabels,
                            datasets: [{
                                    label: 'Kerugian (Rp)',
                                    data: lossExpiredData,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Potensi Kerugian (Rp)',
                                    data: lossUpcomingData,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Tampilkan total kerugian di bawah grafik
                    document.getElementById('totalLoss').innerText =
                        'Perkiraan Total Kerugian Kadaluarsa: Rp ' + new Intl.NumberFormat('id-ID').format(data
                            .total_loss);
                })
                .catch(error => console.error("Error fetching chart data:", error));
        });
    </script>
@endpush

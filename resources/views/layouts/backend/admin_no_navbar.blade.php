<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('backend_theme/') }}/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>{{ $title ?? 'Laravel' }} | {{ env('APP_NAME') ?? 'Laravel' }}</title>

    <meta name="description" content="" />
    <style>
        .custom-alert {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .custom-alert-box {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            text-align: center;
            max-width: 300px;
        }

        .d-none {
            display: none !important;
        }
    </style>
    <!-- Favicon -->
    {{-- <link rel="icon" type="image/x-icon" href="{{ asset('backend_theme/') }}/assets/img/favicon/favicon.ico" /> --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('img/') }}/logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    {{-- <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" /> --}}

    <!-- Icons. Uncomment required icon fonts -->

    <link rel="stylesheet" href="{{ asset('backend_theme/') }}/assets/vendor/fonts/boxicons.css" />
    {{-- <link rel="stylesheet" href="assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="assets/vendor/libs/sweetalert2/sweetalert2.css" /> --}}
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('backend_theme/') }}/assets/vendor/css/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('backend_theme/') }}/assets/vendor/css/rtl/theme-semi-dark.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('backend_theme/') }}/assets/css/demo.css" />
    @stack('css')
    <style>
        .btn-group,
        .btn-group-vertical {
            display: block;
        }

        .layout-navbar .navbar-dropdown.dropdown-notifications .dropdown-notifications-list {
            max-height: 30rem;
        }

        .layout-navbar .navbar-dropdown .dropdown-menu {
            min-width: 22rem;
            overflow: hidden;
        }

        /* @media (min-width: 1200px) .navbar-expand-xl .navbar-nav .dropdown-menu {
            position: absolute;
        } */

        .ps {
            position: relative;
        }

        .ps {
            overflow-anchor: none;
            touch-action: auto;
            overflow: hidden !important;
        }
    </style>
    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ asset('backend_theme/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- Vendor Styles -->
    <link rel="stylesheet"
        href="{{ asset('backend_theme/') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">

    <link rel="stylesheet" href="{{ asset('backend_theme/') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="{{ asset('backend_theme/') }}/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('backend_theme/') }}/assets/js/config.js"></script>
    <style>
        html,
        body {

            background-color: #f4f5fb;
            /* Atur warna default halaman */
        }
    </style>
</head>

<body id="content-fullscreen">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar layout-without-menu">
        <div class="layout-container">
            <!-- Menu -->

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('layouts.backend.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('backend_theme/') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('backend_theme/') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('backend_theme/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('backend_theme/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ asset('backend_theme/') }}/assets/vendor/js/menu.js"></script>
    <script src="{{ asset('backend_theme/') }}/js/bootstrap-notify.min.js"></script>
    <!-- endbuild -->
    @stack('js')
    <!-- Vendors JS -->
    <script src="{{ asset('backend_theme/') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <!-- Main JS -->
    <script src="{{ asset('backend_theme/') }}/assets/js/main.js"></script>
    {{-- //datatable --}}
    <script src="{{ asset('backend_theme/') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('backend_theme/') }}/assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const myModal = new bootstrap.Modal(document.getElementById('autoPopup'));

            @php
                // Periksa apakah pengguna memiliki role "Gudang"
                if (Auth::user()->role == 'Gudang') {
                    $checkSchedule = \App\Models\OpnameSchedule::where('id_wirehouse', Auth::user()->id_wirehouse)->first();
                } else {
                    $checkSchedule = null;
                }
            @endphp

            // Kirim hasil pemeriksaan ke JavaScript
            const isOpnameToday =
                {{ isset($checkSchedule) && intval($checkSchedule->date_schedule) === intval(date('d')) ? 'true' : 'false' }};

            // alert(isOpnameToday);
            // Jika jadwal opname sesuai, tampilkan popup
            if (isOpnameToday) {
                myModal.show();

            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                // responsive: true,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ ",
                    "zeroRecords": "Maaf belum ada data",
                    "info": "Tampilkan data _PAGE_ dari _PAGES_",
                    "infoEmpty": "belum ada data",
                    "infoFiltered": "(saring from _MAX_ total data)",
                    "search": "Cari : ",
                    "paginate": {
                        "previous": "Sebelumnya ",
                        "next": "Selanjutnya"
                    }
                }

            });
        });
        $(document).ready(function() {
            $('#datatable2').DataTable({
                // responsive: true,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ ",
                    "zeroRecords": "Maaf belum ada data",
                    "info": "Tampilkan data _PAGE_ dari _PAGES_",
                    "infoEmpty": "belum ada data",
                    "infoFiltered": "(saring from _MAX_ total data)",
                    "search": "Cari : ",
                    "paginate": {
                        "previous": "Sebelumnya ",
                        "next": "Selanjutnya"
                    }
                }
            });
        });
        $(document).ready(function() {
            $('#datatable-export').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf'
                ]
            });
        });
    </script>
    <script>
        flatpickr("input[type=date]");
    </script>
    @if (Session::has('danger') || Session::has('success'))
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @endif
    @if (Session::has('danger'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: ' {{ Session::get('danger') }}',
                type: 'error',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            })
        </script>
    @endif

    @if (Session::has('success'))
        <script>
            Swal.fire({
                title: 'Good job!',
                text: '{{ Session::get('success') }}',
                type: 'success',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            })
        </script>
    @endif
</body>

</html>

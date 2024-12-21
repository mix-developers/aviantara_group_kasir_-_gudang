@extends('layouts.frontend.app')

@push('css')
@endpush

@section('content')
    <section style="margin: 50px 0 0 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form id="search-form" action="{{ url('/search') }}" method="GET" enctype="multipart/form-data">
                        <div class="input-group shadow-lg">
                            <input type="text" class="form-control form-control-lg rounded-start" name="search"
                                id="search-input" placeholder="Cari Produk..." aria-label="Cari Produk"
                                value="{{ request('search') }}" autofocus>
                            <button class="btn btn-primary px-4" type="submit" id="search-button"><i
                                    class="bi bi-search"></i> Cari</button>
                            <a href="{{ url('/download_all_product') }}" target="__blank" class="btn btn-danger px-4"><i
                                    class="bi bi-file-pdf"></i>
                                PDF</a>
                        </div>
                    </form>
                </div>
            </div>
    </section>

    <section class="pb-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="text-center mb-4">
                <h2>Produk Gudang</h2>
            </div>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="product-list">
                <!-- Konten produk akan dimuat melalui AJAX -->
            </div>
            <div class="mt-3 d-flex justify-content-center" id="pagination-product">
                <!-- Pagination akan dimuat melalui AJAX -->
            </div>
        </div>
    </section>
    <section class="mt-5 bg-light py-2">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="text-center mb-4">
                <h2> Produk Terlaris</h2>
            </div>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center"
                id="top-selling-products-container">
                <!-- Top 5 best-selling products will be loaded via AJAX -->
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchProducts(page = 1, search = '', wirehouse = '-') {
                $.ajax({
                    url: '/get-products?page=' + page,
                    method: 'GET',
                    data: {
                        search: search,
                        wirehouse: wirehouse
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); // Check the structure of the response
                        $('#product-list').html(response.html); // Update the products
                        $('#pagination-product').html(response.pagination); // Update the pagination
                    }
                });
            }

            function fetchTopSellingProducts() {
                $.ajax({
                    url: '/get-top-selling-products', // The route to fetch top-selling products
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Append the HTML content received from the backend into the container
                        $('#top-selling-products-container').html(response.html);
                    }
                });
            }

            // Handle the pagination click event
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var search = $('#search-input').val(); // Get search input value
                var wirehouse = $('#wirehouse-select').val(); // Get selected wirehouse
                fetchProducts(page, search, wirehouse); // Fetch products with search and wirehouse params
            });

            // Handle form submit for search
            $('#search-form').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                var search = $('#search-input').val();
                var wirehouse = $('#wirehouse-select').val();
                fetchProducts(1, search, wirehouse); // Fetch products for the first page with search params
            });

            // Load the first page on page load with search params
            var search = $('#search-input').val();
            var wirehouse = $('#wirehouse-select').val();
            fetchProducts(1, search, wirehouse);

            fetchTopSellingProducts();
        });

        //top selling
    </script>
@endpush

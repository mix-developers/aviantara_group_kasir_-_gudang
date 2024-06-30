@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="" id="alert"></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h1 class="card-title mb-0">{{ $title ?? 'Title' }}</h1>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <h3>Total</h3>
                        <h3 id="totalPrice"><strong>0</strong></h3>
                        <input type="hidden" id="totalPriceInput" name="total_price">
                    </div>
                    <input type="text" id="barcodeInput" name="barcode" placeholder="Scan barcode" class="form-control" style="width: 200px" autofocus="true">
                    <br>
                    <form id="transactionForm">
                        <table id="cartTable" class="table table-hover table-bordered display">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Item</th>
                                    <th>Sub Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                        <br>
                        <h3>Bayar</h3><h3 id="bayarTampil">0</h3>
                        <input type="number" id="bayar" name="bayar" class="form-control bayar" 
                            min="1" required style="width: 200px"> 
                        <br>
                        <h3>Kembalian</h3>
                        <h3 id="kembalian">0</h3>
                        <button type="submit" class="btn btn-secondary btn-primary">Simpan Transaksi</button>
                    </form>
                </div>
                {{-- <div class="card-datatable table-responsive">
                    <table id="datatable-stok-kios" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Transaksi</th>
                                <th>Nama Kios</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Tgl Kadaluarsa</th>
                                <th>Pengguna</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div> --}}

                {{-- <form id="transactionForm">
                    <div id="products">
                        <div class="product">
                            <input type="text" name="barcode[]" class="barcode" placeholder="Barcode">
                            <input type="number" name="quantity[]" class="quantity" placeholder="Quantity" value="1">
                            <span class="product-name"></span>
                            <span class="product-price"></span>
                            <input type="hidden" name="product_id[]" class="product-id">
                            <input type="hidden" name="price[]" class="price">
                        </div>
                    </div>
                    <button type="submit">Submit Transaksi</button>
                </form>
                <div>
                    <h2>Total Price: <span id="totalPrice">0</span></h2>
                </div> --}}

            </div>
        </div>
    </div>
    @include('admin.transaksi.modal')
@endsection
@include('admin.transaksi.script')

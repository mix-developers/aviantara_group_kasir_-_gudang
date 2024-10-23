@extends('layouts.frontend.app')
@push('css')
@endpush
@section('content')
    <section style="margin: 50px 0 50px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ url('/search') }}" method="GET" enctype="multipart/form-data">

                        <div class="input-group shadow-lg">
                            <input type="text" class="form-control form-control-lg rounded-start" name="search"
                                placeholder="Cari Produk..." aria-label="Cari Produk" value="{{ request('search') }}"
                                autofocus>
                            <select class="form-control form-control-lg" name="wirehouse">
                                <option value="-" @if (request('wirehouse') == '-') selected @endif>Semua Gudang
                                </option>
                                @foreach (App\Models\Wirehouse::all() as $item)
                                    <option value="{{ $item->id }}" @if (request('wirehouse') == $item->id) selected @endif>
                                        {{ $item->name }} - {{ $item->address }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary px-4" type="submit"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </form>
                </div>
            </div>
    </section>
    <section cla3s="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="text-center mb-4">
                <h2>Produk Gudang</h2>
            </div>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach ($product as $item)
                    <div class="col mb-5">
                        <div class="card h-100 shadow-sm"><img class="card-img-top"
                                src="{{ $item->photo != null ? Storage::url($item->photo) : asset('img/default.webp') }}"
                                alt="..." style="width: 100%; height:200px; object-fit:cover;" />
                            <div class="card-body p-4">
                                <div class="text-center">

                                    <div><span class="fw-bolder h5">{{ $item->name }} </span><br><span
                                            style="font-size: 12px;"
                                            class="badge bg-{{ App\Models\Product::getStok($item->id) <= 0 ? 'danger' : 'primary' }}">{{ App\Models\Product::getStok($item->id) <= 0 ? 'Habis' : 'Tersedia' }}</span>
                                    </div>
                                    <div class="my-2">
                                        <small class="text-secondary">
                                            {{ $item->wirehouse->name }}
                                        </small>
                                    </div>
                                    <div class="my-2 py-1 bg-warning" style="border-radius: 6px;">
                                        <span
                                            class="text-black fw-bold">{{ App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()? 'Rp ' .number_format(App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()->price_grosir): 'Belum diberi harga' }}</span>
                                    </div>
                                    <span class="fw-bold">
                                        Stok :
                                        {{ App\Models\Product::getStok($item->id) }} {{ $item->unit }}
                                    </span>
                                    <br style="margin:0;"><small
                                        style="font-size: 11px;">{{ $item->quantity_unit . ' ' . $item->sub_unit . ' /' . $item->unit }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($product->count() <= 0)
                    <div class="col-12">
                        <div class="text-center my-4 fw-bold " style="color: grey;">
                            Mohon maaf, Produk belum tersedia..
                        </div>
                    </div>
                @endif
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $product->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </section>
@endsection

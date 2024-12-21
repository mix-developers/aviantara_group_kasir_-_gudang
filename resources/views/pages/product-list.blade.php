@foreach ($products as $item)
    <div class="col mb-5">
        <div class="card h-100 shadow-sm">
            <img class="card-img-top"
                src="{{ $item->photo != null ? Storage::url($item->photo) : asset('img/default.webp') }}" alt="..."
                style="width: 100%; height:200px; object-fit:cover;" />
            <div class="card-body p-4">
                <div class="text-center">
                    <div><span class="fw-bolder h5">{{ $item->name }} </span><br><span style="font-size: 12px;"
                            class="badge bg-{{ App\Models\Product::getStok($item->id) <= 0 ? 'danger' : 'primary' }}">{{ App\Models\Product::getStok($item->id) <= 0 ? 'Habis' : 'Tersedia' }}</span>
                    </div>
                    <div class="my-2"><small class="text-secondary">{{ $item->wirehouse->name }}</small></div>
                    <div class="my-2 py-1 bg-warning" style="border-radius: 6px;">
                        <span
                            class="text-black fw-bold">{{ App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()? 'Rp ' .number_format(App\Models\ProductPrice::where('id_product', $item->id)->latest()->first()->price_grosir): 'Belum diberi harga' }}</span>
                    </div>
                    <span class="fw-bold">Stok : {{ App\Models\Product::getStok($item->id) }} {{ $item->unit }}</span>
                    <br style="margin:0;"><small
                        style="font-size: 11px;">{{ $item->quantity_unit . ' ' . $item->sub_unit . ' /' . $item->unit }}</small>
                </div>
            </div>
        </div>
    </div>
@endforeach

@if ($products->count() <= 0)
    <div class="col-12">
        <div class="text-center my-4 fw-bold " style="color: grey;">Mohon maaf, Produk belum tersedia..</div>
    </div>
@endif

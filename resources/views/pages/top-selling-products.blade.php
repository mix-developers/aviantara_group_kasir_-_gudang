@foreach ($topSellingProducts as $product)
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-light">
            <div class="card-body text-center">
                <b class="card-title">{{ $product->product->name }}</b>
                <p class="card-text">
                <div class="my-2 py-1 bg-warning" style="border-radius: 6px;">
                    <span class="text-black fw-bold">
                        {{ App\Models\ProductPrice::where('id_product', $product->id_product)->latest()->first()? 'Rp ' .number_format(App\Models\ProductPrice::where('id_product', $product->id_product)->latest()->first()->price_grosir): 'Belum diberi harga' }}
                    </span>
                </div>
                </p>
                <small
                    style="font-size: 11px;">{{ $product->product->quantity_unit . ' ' . $product->product->sub_unit . ' /' . $product->product->unit }}</small>
            </div>
        </div>
    </div>
@endforeach

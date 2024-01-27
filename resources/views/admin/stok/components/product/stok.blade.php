@if (App\Models\Product::getStok($product->id) == 0)
    <span class="text-danger">Stok Kosong</span>
@else
    <span class="text-primary fw-bold h3"> {{ number_format(App\Models\Product::getStok($product->id)) }} </span>
    {{ $product->unit }}
    <br>
    <small
        class="badge {{ App\Models\ProductStok::where('id_product', $product->id)->latest()->first()->type == 'Keluar'? 'bg-label-danger': 'bg-label-primary' }}"
        style="font-size: 10px;">
        <i
            class="bx   {{ App\Models\ProductStok::where('id_product', $product->id)->latest()->first()->type == 'Keluar'? 'bx-down-arrow-alt ': 'bx-up-arrow-alt ' }}">
        </i>
        {{ App\Models\ProductStok::where('id_product', $product->id)->latest()->first()->quantity }}
        {{ $product->unit }}
    </small>
@endif

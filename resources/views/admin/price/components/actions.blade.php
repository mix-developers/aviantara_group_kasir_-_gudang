<div class="btn-group d-flex">
    <a href="{{ Auth::user()->role != 'Kasir' ? route('prices.show', $product->id) : route('shop-prices.show', $product->id) }}"
        class="btn btn-sm btn-primary">Riwayat</a>
    @if (Auth::user()->role != 'Owner')
        <button class="btn btn-sm btn-warning" onclick="editPrice({{ $product->id }})">Update Harga</button>
    @endif
</div>

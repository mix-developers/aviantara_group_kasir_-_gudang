<div class="btn-group">
    <a href="{{ route('prices.show', $product->id) }}" class="btn btn-sm btn-primary">Riwayat</a>
    <button class="btn btn-sm btn-warning" onclick="editPrice({{ $product->id }})">Update Harga</button>
</div>

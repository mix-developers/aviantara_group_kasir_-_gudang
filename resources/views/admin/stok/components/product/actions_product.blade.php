<div class="btn-group">
    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-primary">Rincian</a>
    <button class="btn btn-sm btn-warning" onclick="editProduct({{ $product->id }})">Edit</button>
    <button class="btn btn-sm btn-danger " onclick="deleteProduct({{ $product->id }})">Hapus</button>
</div>

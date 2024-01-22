<div class="btn-group">
    <button class="btn btn-sm btn-primary">Rincian Stok</button>
    <button class="btn btn-sm btn-warning" onclick="editProduct({{ $product->id }})">Edit</button>
    <button class="btn btn-sm btn-danger " onclick="deleteProduct({{ $product->id }})">Hapus</button>
</div>

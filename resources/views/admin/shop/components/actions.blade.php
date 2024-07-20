<div class="btn-group">
    <a class="btn btn-sm btn-primary" href="{{ route('shops.show', $shop->id) }}">Data</a>
    <button class="btn btn-sm btn-warning" onclick="editShop({{ $shop->id }})">Edit</button>
    <button class="btn btn-sm btn-danger " onclick="deleteShop({{ $shop->id }})">Hapus</button>
</div>

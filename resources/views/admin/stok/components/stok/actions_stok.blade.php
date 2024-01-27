<div class="btn-group">
    @if ($stok->type == 'Masuk')
        <button class="btn btn-sm btn-warning" onclick="editStok({{ $stok->id }})">Edit</button>
        <button class="btn btn-sm btn-danger " onclick="deleteStok({{ $stok->id }})">Hapus</button>
    @endif
</div>

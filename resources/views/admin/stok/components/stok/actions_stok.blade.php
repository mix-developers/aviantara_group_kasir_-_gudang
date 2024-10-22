<div class="btn-group d-flex">
    @if ($stok->type == 'Masuk' && $stok->sub_type == 'masuk')
        <button class="btn btn-sm btn-warning" onclick="editStok({{ $stok->id }})">Edit</button>
        <button class="btn btn-sm btn-danger " onclick="deleteStok({{ $stok->id }})">Hapus</button>
    @endif
</div>

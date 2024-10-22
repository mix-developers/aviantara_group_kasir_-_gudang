<div class="btn-group d-flex">
    <button class="btn btn-sm btn-primary " onclick="showDamaged({{ $damaged->id }})">Lihat</button>
    @if (Auth::user()->role == 'Gudang')
        <button class="btn btn-sm btn-danger " onclick="deleteDamaged({{ $damaged->id }})">Hapus</button>
    @endif
</div>

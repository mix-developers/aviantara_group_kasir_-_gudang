<div class="btn-group">
    <a href="{{ route('wirehouses.show', $wirehouse->id) }}" class="btn btn-sm btn-primary">Data</a>
    @if (Auth::user()->role != 'Owner')
        <button class="btn btn-sm btn-warning" onclick="editWirehouse({{ $wirehouse->id }})">Edit</button>
        <button class="btn btn-sm btn-danger " onclick="deleteWirehouse({{ $wirehouse->id }})">Hapus</button>
    @endif
</div>

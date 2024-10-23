<div class="btn-group d-flex">
    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-primary">Data</a>
    @if (Auth::user()->role != 'Owner')
        <button class="btn btn-sm btn-warning" onclick="editCustomer({{ $customer->id }})">Edit</button>
        <button class="btn btn-sm btn-danger " onclick="deleteCustomers({{ $customer->id }})">Hapus</button>
    @endif
</div>

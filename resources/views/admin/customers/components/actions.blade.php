<div class="btn-group">
    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-primary">Data</a>
    <button class="btn btn-sm btn-warning" onclick="editCustomer({{ $customer->id }})">Edit</button>
    <button class="btn btn-sm btn-danger " onclick="deleteCustomers({{ $customer->id }})">Hapus</button>
</div>

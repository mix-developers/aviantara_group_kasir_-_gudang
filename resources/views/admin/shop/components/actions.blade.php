<div class="btn-group">
    <button class="btn btn-sm btn-primary">Data</button>
    <button class="btn btn-sm btn-warning" onclick="editCustomer({{ $customer->id }})">Edit</button>
    <button class="btn btn-sm btn-danger " onclick="deleteCustomers({{ $customer->id }})">Hapus</button>
</div>

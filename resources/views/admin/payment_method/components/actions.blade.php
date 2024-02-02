<div class="btn-group">
    <a href="{{ route('paymentMethod.show', $PaymentMethod->id) }}" class="btn btn-sm btn-primary">Data</a>
    <button class="btn btn-sm btn-warning" onclick="editPaymentMethod({{ $PaymentMethod->id }})">Edit</button>
    <button class="btn btn-sm btn-danger " onclick="deletePaymentMethod({{ $PaymentMethod->id }})">Hapus</button>
</div>

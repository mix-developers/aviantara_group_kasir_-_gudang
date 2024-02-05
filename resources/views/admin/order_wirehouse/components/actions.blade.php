<div class="btn-group">
    <a href="" class="btn btn-sm btn-primary"><span>
            <i class="bx bx-car "> </i></a>
    <button class="btn btn-sm btn-warning" onclick="editOrder({{ $OrderWirehouse->id }})"><span>
            <i class="bx bx-edit "> </i></button>
    <a type="button" class="btn btn-sm btn-secondary "
        href="{{ route('payments.invoice', $OrderWirehouse->no_invoice) }}">
        <span class="tf-icons bx bx-printer"></span>
    </a>
    <button class="btn btn-sm btn-danger " onclick="deleteOrder({{ $OrderWirehouse->id }})"> <i class="bx bx-trash ">
        </i></button>
</div>

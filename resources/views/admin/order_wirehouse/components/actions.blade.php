<div class="btn-group">
    <a href="" class="btn btn-sm btn-primary"><span>
            <i class="bx bx-car "> </i></a>
    <button class="btn btn-sm btn-warning" onclick="editOrder({{ $OrderWirehouse->id }})"><span>
            <i class="bx bx-edit "> </i></button>
    <button class="btn btn-sm btn-danger " onclick="deleteOrder({{ $OrderWirehouse->id }})"> <i class="bx bx-trash ">
        </i></button>
</div>

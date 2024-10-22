@if (Auth::user()->role == 'Gudang')
    <div class="btn-group d-flex justify-content-center">
        <button class="btn btn-sm btn-warning" onclick="editOrder({{ $OrderWirehouse->id }})"><span>
                <i class="bx bx-edit "> </i></button>
        <a class="btn btn-sm btn-secondary " href="#"
            onclick="window.open('{{ url('/order_wirehouses/print-invoice', $OrderWirehouse->id) }}', 'Print Invoice', 'width=800,height=600')">
            <span class="tf-icons bx bx-printer"></span>
        </a>
        <button class="btn btn-sm btn-danger " onclick="deleteOrder({{ $OrderWirehouse->id }})"> <i class="bx bx-trash ">
            </i></button>
    </div>
@else
    <div class="btn-group d-flex justify-content-center">
        <a type="button" class="btn btn-primary "
            href="{{ route('order_wirehouses.invoice', $OrderWirehouse->no_invoice) }}">
            <span class="tf-icons bx bx-file"></span>
        </a>
    </div>
@endif

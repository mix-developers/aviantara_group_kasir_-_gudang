<div class="btn-group">
    @if (Auth::user()->role == 'Gudang')
        <button type="button"
            class="btn rounded-pill btn-icon btn-{{ $OrderWirehouse->send_bill == 0 ? 'success' : 'outline-secondary' }}"
            onclick="sendOrderWirehouse({{ $OrderWirehouse->id }})">
            <span
                class="tf-icons bx bxl-whatsapp {{ $OrderWirehouse->send_bill == 0 ? 'text-white' : 'text-secondary' }}"></span>
        </button>
        <button type="button" class="btn rounded-pill btn-icon btn-warning"
            onclick="addPayment({{ $OrderWirehouse->id }})" {{ $paid == $OrderWirehouse->total_fee ? 'disabled' : '' }}>
            <span class="tf-icons bx bx-plus"></span>
        </button>
    @endif
    <a type="button" class="btn rounded-pill btn-icon btn-primary "
        href="{{ route('order_wirehouses.invoice', $OrderWirehouse->no_invoice) }}">
        <span class="tf-icons bx bx-file"></span>
    </a>
    @if ($OrderWirehouse->delivery == 1)
        <a class="btn rounded-pill btn-icon btn-secondary " href="#"
            onclick="window.open('{{ url('/payments/print-delivery', $OrderWirehouse->id) }}', 'Print Invoice', 'width=800,height=600')">
            <span class="tf-icons bx bxs-truck"></span>
        </a>
    @endif
</div>

<div class="btn-group">
    <button type="button"
        class="btn rounded-pill btn-icon btn-{{ $OrderWirehouse->send_bill == 0 ? 'success' : 'outline-secondary' }}"
        onclick="sendOrderWirehouse({{ $OrderWirehouse->id }})">
        <span
            class="tf-icons bx bxl-whatsapp {{ $OrderWirehouse->send_bill == 0 ? 'text-white' : 'text-secondary' }}"></span>
    </button>
    <button type="button" class="btn rounded-pill btn-icon btn-warning" onclick="addPayment({{ $OrderWirehouse->id }})"
        {{ $paid == $OrderWirehouse->total_fee ? 'disabled' : '' }}>
        <span class="tf-icons bx bx-plus"></span>
    </button>
    <a type="button" class="btn rounded-pill btn-icon btn-primary "
        href="{{ route('payments.invoice', $OrderWirehouse->no_invoice) }}">
        <span class="tf-icons bx bx-file"></span>
    </a>
</div>

<a href="javascript:void(0)">
    @if ($stok->expired_date <= date('Y-m-d'))
        Kadaluarsa
    @elseif($stok->expired_date <= date('Y-m-d', strtotime('+3 month')))
        Akan Kadaluarsa
    @else
        Aman
    @endif
</a>

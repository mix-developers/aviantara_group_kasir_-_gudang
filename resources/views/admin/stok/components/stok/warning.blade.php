<a href="javascript:void(0)">
    <i class="bx bx-info-circle  
    @if ($stok->expired_date <= date('Y-m-d')) text-danger 
    @elseif($stok->expired_date <= date('Y-m-d', strtotime('+3 month'))) text-warning
    @else text-success @endif"
        data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Status Expired"
        data-bs-original-title="Status Expired">
    </i>
</a>

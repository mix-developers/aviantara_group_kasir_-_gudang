<script>
    var dataTable;
    $(function() {
        $('.create-new-retail').click(function() {
            $('#create_retail').modal('show');
            getWirehouseOptions();
            // discount
            $('#selectOrderDiscount').on('change', function() {
                const selectedMethodOrderDiscount = $(this).val();

                if (selectedMethodOrderDiscount === 'persen') {
                    $('#divOrderDiscountPersen').show();
                    $('#divOrderDiscountRupiah').hide();
                    $('#orderDiscountRupiah').val(0);
                } else if (selectedMethodOrderDiscount ===
                    'rupiah') {
                    $('#divOrderDiscountPersen').hide();
                    $('#divOrderDiscountRupiah').show();
                    $('#orderDiscountPersen').val(0);
                }
            });
            // -------
        });
    });
</script>

@push('js')
    <script>
        //stok gudang
        $(function() {
            $('.refresh-stok').click(function() {
                getStok().ajax.reload;

            });

            function getStok() {
                $.ajax({
                    type: 'GET',
                    url: '/get-stok-card',
                    success: function(response) {
                        $('#stokInput').text(response.stok_input);
                        $('#stokOut').text(response.stok_out);
                        $('#stokExpired').text(response.stok_expired);
                        $('#stokNotExpired').text(response.stok_not_expired);
                        $('#stokWirehouse').text(response.stok_wirehouse);
                        $('#priceStokInput').text(formatNumberWithDot(response.price_stok_input));
                    }
                });
            };

            function expiredAlert() {
                $.ajax({
                    type: 'GET',
                    url: '/expired-alert',
                    success: function(response) {
                        var expiredText = '<span class="h4 text-danger"><i class="bx bx-error"></i> ' +
                            response.expired +
                            ' Barang pada gudang telah kadaluarsa ..</span>';
                        var remainingText = '<strong><i class="bx bx-error-circle"></i> ' + response
                            .remaining +
                            ' Barang pada gudang akan kadaluarsa..</strong><br><small>*Waktu peringatan dihitung 3 bulan sebelum tanggal kadaluarsa tiba.</small>';
                        if (response.expired != 0) {
                            getAlert(expiredText, 'danger');
                        }
                        if (response.remaining != 0) {
                            getAlert(remainingText, 'warning');

                        }
                    }
                });
            };

            function notPriceAlert() {
                $.ajax({
                    type: 'GET',
                    url: '/prices/get-not-price',
                    success: function(response) {
                        var text = '<strong><i class="bx bx-error-circle"></i> ' + response +
                            ' Produk belum diberikan harga</small>';
                        if (response !== 0) {
                            getAlert(text, 'danger');

                        }
                    }
                });
            };

            function getAlert(alertValue, type) {
                $('#alert').append(
                    '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }

            function formatNumberWithDot(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            notPriceAlert();
            getStok();
            expiredAlert();
            //end stok gudang
        });
    </script>
@endpush

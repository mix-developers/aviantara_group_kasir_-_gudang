<script src="{{ asset('assets/barcode/zxing.min.js') }}"></script>
{{-- <script src="https://unpkg.com/@zxing/library@latest"></script> --}}
<script>
    $(document).ready(function() {
        const codeReader = new ZXing.BrowserBarcodeReader();
        const videoElement = document.getElementById('barcode-scanner');

        $('#enabledScanner').change(function() {
            if (this.checked) {
                $('#scanner').show();
                $('#formCreateProductBarcode').prop('readonly', true);
                $('#formProductBarcode').prop('readonly', true);
                $('#createProductBtn').prop('disabled', true);

                //suara ketika sukses
                const beepSound = new Audio('{{ asset('assets/sounds/beep.wav') }}');
                //mulai scan
                codeReader.decodeFromVideoDevice(undefined, 'barcode-scanner', (result, err) => {
                    if (result) {
                        beepSound.play();
                        console.log(`Detected barcode: ${result.text}`);
                        document.getElementById('formCreateProductBarcode').value = result.text;
                        document.getElementById('formProductBarcode').value = result.text;
                        $('#createProductBtn').prop('disabled', false);
                    }
                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        console.error(err);
                    }
                });
            } else {
                $('#formCreateProductBarcode').prop('readonly', false);
                $('#formProductBarcode').prop('readonly', false);
                $('#createProductBtn').prop('disabled', false);
                $('#scanner').hide();

                // reset scan
                codeReader.reset();
            }
        });
    });
</script>

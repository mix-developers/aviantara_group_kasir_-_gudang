<!-- Modal for Create and Edit -->
<div class="modal fade" id="productsModal" tabindex="-1" aria-labelledby="shopsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shopModalLabel">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="productForm" enctype="multipart/form-data">
                    <input type="hidden" id="formProductId" name="id">
                    <div class="mb-3">
                        <label for="formProductName" class="form-label">Foto Produk (kosongkan jika tidak
                            merubah)</label>
                        <input type="file" class="form-control" id="formProductPhoto" name="photo">
                    </div>
                    <div class="mb-3">
                        <label for="formProductName" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="formProductName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formProductBarcode" class="form-label">Barcode <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="formProductBarcode" name="barcode" value="0"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formProductUnit" class="form-label">Satuan Produk <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="formProductUnit" name="unit" required>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="formProductQUantityUnit" class="form-label">Jumlah isi per-satuan
                            <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="formProductQUantityUnit" name="quantity_unit"
                                required>
                            <select class="form-select" id="formProductSubUnit" name="sub_unit" required>

                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">
                    <div class="spinner-border spinner-border-sm text-white" role="status" id="saveProductBtnSpinner"
                        style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="create" tabindex="-1" aria-labelledby="productsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Tambah Produk </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createProductForm" enctype="multipart/form-data">
                    {{-- barcode scanner --}}
                    <div class="mb-3" id="scanner" style="display:none;">
                        <center>
                            <video id="barcode-scanner" playsinline style="width: 100%; max-width: 400px; "></video>
                        </center>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch  ">
                            <input class="form-check-input" type="checkbox" id="enabledScanner">
                            <label class="form-check-label" for="enabledScanner">Barcode Scanner
                            </label>
                        </div>
                        <small class="text-muted">*Aktifkan jika ingin menggunakan fitur barcode scanner</small>
                    </div>
                    {{-- end barcode scanner --}}
                    <div class="my-3">
                        <div class="input-group">
                            <button type="button" class="btn btn-sm btn-primary" id="in-wirehouse">Dari
                                Gudang</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="out-wirehouse">Luar
                                Gudang</button>
                        </div>
                    </div>
                    <button type="button" class="btn w-100 btn-primary" id="selectProduct"><i
                            class="bx bx-search"></i>
                        Pilih
                        Produk</button>
                    <div class="mb-3">
                        <label for="formProductBarcode" class="form-label">Barcode <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" autofocus id="formCreateProductBarcode"
                                name="barcode" required>
                            <button type="button" class="btn btn-secondary" id="generateBarcodeBtn">
                                Generate
                            </button>
                        </div>
                        <small class="text-muted">*jika produk tidak memiliki barcode, klik generate barcode<br> *jika
                            produk berasal dari gudang utama, isi barcode sesuai dari gudang utama</small>
                    </div>
                    <div class="mb-3">
                        <label for="formProductName" class="form-label">Foto Produk (dapat dikosongkan)</label>
                        <input type="file" class="form-control" id="formCreateProductPhoto" name="photo">
                    </div>
                    <div class="mb-3">
                        <label for="formProductName" class="form-label">Nama <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control " id="formCreateProductName" name="name"
                            required>
                    </div>


                    <div class="mb-3">
                        <label for="formProductUnit" class="form-label">Satuan Produk <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="formCreateProductUnit" name="unit" required>
                            <option value="Karung">Karung</option>
                            <option value="Karton">Karton</option>
                            <option value="Bal">Bal</option>
                            <option value="Pak">Pak</option>
                            <option value="Rak">Rak</option>
                            <option value="Koli">Koli</option>
                            <option value="Kg">Kg</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formProductQUantityUnit" class="form-label">Jumlah isi per-satuan
                            <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="formCreateProductQUantityUnit"
                                name="quantity_unit" required>
                            <select class="form-select" id="formCreateProductSubUnit" name="sub_unit" required>
                                <option value="Kg">Kg</option>
                                <option value="Gram">Gram</option>
                                <option value="Ons">Ons</option>
                                <option value="Liter">Liter</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Kodi">Kodi</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Butir">Butir</option>
                                <option value="Bungkus">Bungkus</option>
                                <option value="Lusin">Lusin</option>
                                <option value="Gros">Gros</option>
                                <option value="Ekor">Ekor</option>
                                <option value="Buah">Buah</option>
                                <option value="Sacet">Sacet</option>
                                <option value="Botol">Botol</option>
                                <option value="Kaleng">Kaleng</option>
                                <option value="Gelas">Gelas</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="resetFormBtn">Reset Form</button>
                <button type="button" class="btn btn-primary" id="createProductBtn" @disabled(false)>
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createProductBtnSpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Pilih Produk -->
<div class="modal fade" id="selectProductModal" tabindex="-1" aria-labelledby="selectProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Produk Dari Gudang Besar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body table-responsive">
                <table id="productSelectTable" class="table table-bordered table-striped table-sm" width="100%">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.getElementById('selectProduct').addEventListener('click', function() {
            $('#selectProductModal').modal('show');
            $('#productSelectTable').DataTable().ajax.reload(); // reload data setiap buka
        });
        $(function() {
            // select produk
            $('#productSelectTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('datatable-stock-main-wirehouse') }}', // Ganti dengan route API kamu
                columns: [{
                        data: 'barcode'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-success select-product-btn" 
                                    data-barcode="${row.barcode}">
                                Pilih
                            </button>`;
                        }
                    }
                ]
            });
            $(document).on('click', '.select-product-btn', function() {
                const barcode = $(this).data('barcode');

                $('#formCreateProductBarcode').val(barcode).trigger('input').trigger('change');

                $('#selectProductModal').modal('hide');
            });

            function getAlert(alertValue) {
                $('#alert').append(
                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }


        });
        document.addEventListener('DOMContentLoaded', function() {
            const fromWarehouseBtn = document.getElementById('in-wirehouse');
            const outWarehouseBtn = document.getElementById('out-wirehouse');
            const formInputs = document.querySelectorAll('#createProductForm input, #createProductForm select');
            const barcodeInput = document.getElementById('formCreateProductBarcode');
            const scannerCheckbox = document.getElementById('enabledScanner');
            const scannerSection = document.getElementById('scanner');
            const selectProductBtn = document.getElementById('selectProduct');

            function setFromWarehouseMode() {
                formInputs.forEach(input => {
                    if (
                        input !== barcodeInput &&
                        input !== scannerCheckbox
                    ) {
                        input.setAttribute('readonly', true);
                        input.removeAttribute('disabled');
                    }
                });
                barcodeInput.removeAttribute('readonly');
                barcodeInput.removeAttribute('disabled');
                scannerCheckbox.removeAttribute('readonly');
                scannerCheckbox.removeAttribute('disabled');

                fromWarehouseBtn.classList.replace('btn-outline-primary', 'btn-primary');
                outWarehouseBtn.classList.replace('btn-primary', 'btn-outline-primary');
                // Tampilkan tombol selectProduct
                selectProductBtn.classList.remove('d-none');


            }

            function setOutWarehouseMode() {
                formInputs.forEach(input => {
                    input.removeAttribute('readonly');
                    input.removeAttribute('disabled');
                });

                fromWarehouseBtn.classList.replace('btn-primary', 'btn-outline-primary');
                outWarehouseBtn.classList.replace('btn-outline-primary', 'btn-primary');
                // Sembunyikan tombol selectProduct
                selectProductBtn.classList.add('d-none');

            }

            fromWarehouseBtn.addEventListener('click', setFromWarehouseMode);
            outWarehouseBtn.addEventListener('click',
                setOutWarehouseMode);

            // Set default mode to 'Dari Gudang'
            setFromWarehouseMode();
        });
    </script>
@endpush

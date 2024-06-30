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
                <form id="productForm">
                    <input type="hidden" id="formProductId" name="id">
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
                    <div class="mb-3">
                        <label for="formProductIdWirehouse" class="form-label">Pilih Gudang <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="formProductIdWirehouse" name="id_wirehouse" required>

                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">Save</button>
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
                <form id="createProductForm">
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
                    <div class="mb-3">
                        <label for="formProductName" class="form-label">Nama <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control " id="formProductName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formProductBarcode" class="form-label">Barcode <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="formCreateProductBarcode" name="barcode"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formProductUnit" class="form-label">Satuan Produk <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="formProductUnit" name="unit" required>
                            <option value="Karung">Karung</option>
                            <option value="Karton">Karton</option>
                            <option value="Bal">Bal</option>
                            <option value="Pak">Pak</option>
                            <option value="Rak">Rak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formProductQUantityUnit" class="form-label">Jumlah isi per-satuan
                            <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="formProductQUantityUnit"
                                name="quantity_unit" required>
                            <select class="form-select" id="formProductSubUnit" name="sub_unit" required>
                                <option value="Pcs">Pcs</option>
                                <option value="Karton">Karton</option>
                                <option value="Buah">Buah</option>
                                <option value="Sacet">Sacet</option>
                                <option value="Botol">Botol</option>
                                <option value="Gelas">Gelas</option>
                                <option value="Bungkus">Bungkus</option>
                                <option value="Butir">Butir</option>
                                <option value="Rim">Rim</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Gross">Gross</option>
                                <option value="Lusin">Lusin</option>
                                <option value="Kodi">Kodi</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formProductIdWirehouse" class="form-label">Pilih Gudang <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="formProductIdWirehouseCreate" name="id_wirehouse" required>

                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createProductBtn"
                    @disabled(false)>Save</button>
            </div>
        </div>
    </div>
</div>

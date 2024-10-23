i<!-- Modal for Create and Edit -->
<div class="modal fade" id="stoksModal" tabindex="-1" aria-labelledby="shopsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shopModalLabel">Edit Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="stokForm">
                    {{-- <button type="button" class="btn btn-primary select-produk">Pilih
                        Produk</button> --}}
                    <input type="hidden" id="formStoktId" name="id">
                    <input type="hidden" id="formProductId" name="id_product">
                    <div class="my-3" id="descriptionStok">
                    </div>

                    <div class="mb-3">
                        <label for="formStokQuantity" class="form-label">Jumlah Stok <span>*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="formStokQuantity" name="quantity">
                            <span class="input-group-text" id="formStokUnit">Satuan</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formStokPrice" class="form-label">Harga <spam id="formStokUnit2"
                                class="text-primary">
                            </spam>
                            <span>*</span></label>
                        <input type="number" class="form-control" id="formStokPrice" name="price_origin">
                    </div>
                    <div class="mb-3">
                        <label for="formStokExpiredDate" class="form-label">Tanggal Kadaluarsa
                            <span>*</span></label>
                        <input type="date" class="form-control" id="formStokExpiredDate" name="expired_date">
                    </div>
                    <input type="hidden" name="description" value="Barang Masuk" id="formStokDescription">
                    <input type="hidden" name="type" value="Keluar" id="formStokType">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStokBtn">
                    <div class="spinner-border spinner-border-sm text-white" role="status" id="saveStokBtnSpinner"
                        style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="create" tabindex="-1" aria-labelledby="productsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Tambah Stok </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createStokForm">
                    <button type="button" class="btn btn-primary select-produk"><i class="bx bx-check"></i> Pilih
                        Produk</button>
                    <button type="button" class="btn btn-warning create-product"><i class="bx bx-plus"></i> Tambah
                        Produk</button>
                    <input type="hidden" id="formCreateProductId" name="id_product">
                    <div class="my-3" id="descriptionCreateStok">
                        <div class="alert alert-danger alert-dismissible my-4" role="alert">
                            <span>*Silahkan pilih produk terlebih dahulu</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        {{-- <label for="formCreateStokName" class="form-label">Nama Produk<span>*</span></label> --}}
                        <input type="hidden" class="form-control" id="formCreateStokName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateStokQuantity" class="form-label">Barcode
                            <span>*</span></label>
                        <input type="number" class="form-control" id="formCreateStokBarcode" name="barcode"
                            autofocus="true">
                    </div>
                    <div class="mb-3">
                        <label for="formCreateStokQuantity" class="form-label">Jumlah Stok
                            <span>*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="formCreateStokQuantity" name="quantity">
                            <span class="input-group-text" id="formCreateStokUnit">Satuan</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateStokPrice" class="form-label">Harga <span id="formCreateStokUnit2"
                                class="text-primary">
                            </span>
                            <span>*</span></label>
                        <input type="number" class="form-control" id="formCreateStokPrice" name="price_origin">
                    </div>
                    <div class="mb-3">
                        <label for="formCreateStokExpiredDate" class="form-label">Tanggal Kadaluarsa
                            <span>*</span></label>
                        <input type="date" class="form-control" id="formCreateStokExpiredDate"
                            name="expired_date">
                    </div>
                    <input type="hidden" name="description" value="Barang Masuk" id="formCreateStokDescription">
                    <input type="hidden" name="type" value="Keluar" id="formCreateStokType">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createStokBtn">
                    <div class="spinner-border spinner-border-sm text-white" role="status" id="createStokBtnSpinner"
                        style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="productSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-left:5px; padding-right:5px;">
                <div class="table-responsive">
                    <table id="productSelectionTable" class="table table-hover display table-sm">
                        <thead>
                            <tr>
                                <th style="width: 5px;">ID</th>
                                <th>Nama Produk</th>
                                <th>Barcode</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md selectProduct">Pilih</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create-product" tabindex="-1" aria-labelledby="productsModalLabel" aria-hidden="true">
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
                        <input type="text" class="form-control" id="formProductName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formProductBarcode" class="form-label">Barcode <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="formProductBarcode" name="barcode" required>
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
                <button type="button" class="btn btn-primary" id="createProductBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Create and Edit -->

<div class="modal fade" id="stok_form" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customersModalLabel">Tambah Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Form for Create and Edit -->
                <form id="stokForm">
                    {{-- barcode scanner --}}
                    <div class="mb-3" id="scanner" style="display:none;">
                        <center>
                            <video id="barcode-scanner" playsinline style="width: 100%; max-width: 400px; "></video>
                        </center>
                        <hr>
                    </div>
                    <div class="d-flex mb-3 justify-content-between">
                        <div class="mb-3">
                            <div class="form-check form-switch  ">
                                <input class="form-check-input" type="checkbox" id="enabledScanner">
                                <label class="form-check-label" for="enabledScanner">Barcode Scanner
                                </label>
                            </div>
                            <small class="text-muted">*Aktifkan jika ingin menggunakan fitur barcode scanner</small>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary" id="selectProduct"><i
                                    class="bx bx-search"></i> <span class="d-none d-sm-inline-block"> Pilih
                                    Produk</span></button>
                        </div>
                    </div>

                    {{-- end barcode scanner --}}
                    <input type="hidden" id="formStokId" name="id">
                    <input type="hidden" id="formUserId" name="id_user">
                    <input type="hidden" id="formKiosId" name="id_kios" value="{{ Auth::user()->id_shop }}">
                    <input type="hidden" id="formIdProduk" name="id_product_add">


                    <div class="mb-3">
                        <label for="formBarcode" class="form-label">Barcode</label>
                        <input type="text" class="form-control" id="formProductBarcode" name="barcode" required>
                    </div>


                    <div class="mb-3">
                        <label for="formNamaProduk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="formNamaProduk" name="nama_product" required
                            readonly>
                    </div>

                    <div class="mb-3 input group">
                        <label for="formStokQty" class="form-label">Qty</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="formStokQty" name="qty" required>
                            <span class="input-group-text" id="txtUnit">Unit</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formStokPrice" class="form-label">Harga</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="formStokPrice" name="price" required>
                            <span class="input-group-text" id="txtUnitPrice">/Unit</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formStokExpired" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" class="form-control" id="formStokExpired" name="expired_date" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStokBtn">Save</button>
            </div>
        </div>
    </div>
</div>


{{-- MODAL EDIT --}}
<div class="modal fade" id="kios_stok_modal" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stokModalLabel"> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="editStokForm">
                    <input type="hidden" id="editStokId" name="id">
                    <input type="hidden" id="formUserId" name="id_user">
                    <input type="hidden" id="editIdProduk" name="id_product">

                    <div class="mb-3">
                        <label for="editNamaKios" class="form-label">Nama Kios</label>
                        <input type="text" class="form-control" id="editNamaKios" name="id_kios" required>
                    </div>
                    <div class="mb-3">
                        <label for="editKodeProduk" class="form-label">Kode Produk</label>
                        <input type="text" class="form-control" id="editKodeProduk" name="kode_product" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNamaProduk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="editNamaProduk" name="id_product" required>
                    </div>

                    <div class="mb-3">
                        <label for="editStokQty" class="form-label">Qty</label>
                        <input type="number" class="form-control" id="editStokQty" name="qty" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStokPrice" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="editStokPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStokExpired" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" class="form-control" id="editStokExpired" name="expired_date"
                            required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editStokBtn">Update</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="#" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Tambah Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createUserForm">
                    <div class="mb-3">
                        <label for="formCustomerName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formCustomerName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerPhone" class="form-label">No Hp</label>
                        <input type="number" class="form-control" id="formCustomerPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerAddressHome" class="form-label">Alamat Rumah</label>
                        <input type="text" class="form-control" id="formCustomerAddressHome" name="address_home"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerAddressCompany" class="form-label">Alamat Usaha</label>
                        <input type="text" class="form-control" id="formCustomerAddressCompany"
                            name="address_company" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createCustomerBtn">Save</button>
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
                <h5 class="modal-title">Pilih Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body table-responsive">
                <table id="productSelectTable" class="table table-bordered table-striped" width="100%">
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

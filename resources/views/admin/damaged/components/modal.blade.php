<div class="modal fade" id="create" tabindex="-1" aria-labelledby="DamagedsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Tambah Data Kerusakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createDamagedForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary select-produk"><i class="bx bx-check"></i> Pilih
                            Produk</button>
                    </div>
                    <div class="my-3" id="descriptionCreateStok">
                        <div class="alert alert-danger alert-dismissible my-4" role="alert">
                            <span>*Silahkan pilih produk terlebih dahulu</span>
                        </div>
                    </div>
                    <input type="hidden" name="id_product" id="createFormIdProduct">
                    <div class="mb-3">
                        <label for="formPhoto" class="form-label">Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="formPhoto" name="photo" accept="image/*"
                            required>
                        <small class="text-muted">Unggah foto kerusakan (format: jpeg, png, jpg, gif, max 2MB)</small>
                    </div>
                    <div class="mb-3">
                        <label for="formPhoto" class="form-label">Foto 2 (opsional)</label>
                        <input type="file" class="form-control" id="formPhoto2" name="photo2" accept="image/*"
                            required>
                        <small class="text-muted">Unggah foto kerusakan (format: jpeg, png, jpg, gif, max 2MB)</small>
                    </div>
                    <div class="mb-3">
                        <label for="formType" class="form-label">Jenis Kerusakan <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="formType" name="type" required>
                            <option value="Kadaluarsa">Kadaluarsa</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Kerusakan <span id="formCreateStokUnit2" class="text-primary">/
                                Satuan</span><span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="formQuantityUnit" name="quantity_unit"
                                required>
                            <span class="input-group-text" id="formCreateStokUnit">Satuan</span>
                        </div>
                    </div>
                    <div class="mb-3" id="jeniRusak" style="display: none;">
                        <label>Jumlah Kerusakan <span id="formCreateSubUnit" class="text-primary">/
                                Satuan</span></label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="formQuantitySubUnit"
                                name="quantity_sub_unit">
                            <span class="input-group-text" id="formCreateSubUnit2">Satuan</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="formExpiredDate" class="form-label">Tanggal Kadaluarsa <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="selectExpired" name="expired_date" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formDescription" class="form-label">Keterangan Kerusakan <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="formDescription" name="description" rows="3" required>-</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createDamagedBtn">
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createDamagedBtnSpinner" style="display: none;">
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
                <table id="productSelectionTable" class="table table-hover display table-sm">
                    <thead>
                        <tr>
                            <th style="width: 5px;">ID</th>
                            <th>Nama Produk</th>
                            <th>Barcode</th>
                            <th>Satuan</th>
                            <th>Sub Satuan</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md selectProduct">Pilih</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="show" tabindex="-1" aria-labelledby="productSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-left:5px; padding-right:5px;">
                <!-- Placeholder for images -->
                <img id="photo1" src="" alt="Photo 1" style="width: 100%; height: auto;" />
                <img id="photo2" src=""
                    alt="Photo 2"style="width: 100%; height: auto; margin-top: 10px;  display:none;" />
                <div class="mt-2">
                    <h4>keterangan :</h4>
                    <p id="description_show"></p>
                </div>
            </div>
        </div>
    </div>
</div>

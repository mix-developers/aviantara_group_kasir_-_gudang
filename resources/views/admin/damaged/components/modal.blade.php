<!-- Modal for Create and Edit -->
<div class="modal fade" id="damagedsModal" tabindex="-1" aria-labelledby="DamagedsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Edit Data Kerusakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="damagedForm">
                    <input type="hidden" id="formDamagedId" name="id">
                    <div class="mb-3">
                        <label for="formDamagedName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formDamagedName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formDamagedPhone" class="form-label">No Hp</label>
                        <input type="number" class="form-control" id="formDamagedPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="formDamagedAddressHome" class="form-label">Alamat Rumah</label>
                        <input type="text" class="form-control" id="formDamagedAddressHome" name="address_home"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formDamagedAddressCompany" class="form-label">Alamat Usaha</label>
                        <input type="text" class="form-control" id="formDamagedAddressCompany" name="address_company"
                            required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveDamagedBtn">Save</button>
            </div>
        </div>
    </div>
</div>
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
                        <label for="formIdProduct" class="form-label">Pilih Produk</label>
                        <select class="form-select" id="formIdProduct" name="id_product">
                            @foreach (App\Models\Product::where('id_wirehouse', Auth::user()->id_wirehouse)->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formPhoto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="formPhoto" name="photo" accept="image/*"
                            required>
                        <small class="text-muted">Unggah foto kerusakan (format: jpeg, png, jpg, gif, max 2MB)</small>
                    </div>
                    <div class="mb-3">
                        <label for="formType" class="form-label">Jenis Kerusakan</label>
                        <select class="form-select" id="formType" name="type" required>
                            <option value="Kadaluarsa">Kadaluarsa</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="formTotal" class="form-label">Total</label>
                                <input type="text" class="form-control" id="formTotal" name="total" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="formSatuan" class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="formSatuan" name="satuan" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formExpiredDate" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" class="form-control" id="formExpiredDate" name="expired_date"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formDescription" class="form-label">Keterangan Kerusakan</label>
                        <textarea class="form-control" id="formDescription" name="description" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createDamagedBtn">Save</button>
            </div>
        </div>
    </div>
</div>

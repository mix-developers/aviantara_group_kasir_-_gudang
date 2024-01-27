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
                <form id="createDamagedForm">
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
                <button type="button" class="btn btn-primary" id="createDamagedBtn">Save</button>
            </div>
        </div>
    </div>
</div>

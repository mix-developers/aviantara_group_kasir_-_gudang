<!-- Modal for Create and Edit -->
<div class="modal fade" id="wirehousesModal" tabindex="-1" aria-labelledby="wirehousesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wirehouseModalLabel">Edit Gudang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="wirehouseForm">
                    <input type="hidden" id="formWirehouseId" name="id">
                    <div class="mb-3">
                        <label for="formWirehouseName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formWirehouseName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formWirehouseAddress" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="formWirehouseAddress" name="address" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveWirehouseBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="wirehousesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wirehouseModalLabel">Tambah Gudang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createWirehouseForm">
                    <div class="mb-3">
                        <label for="formWirehouseName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formWirehouseName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formWirehouseAddress" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="formWirehouseAddress" name="address" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createWirehouseBtn">Save</button>
            </div>
        </div>
    </div>
</div>

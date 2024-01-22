<!-- Modal for Create and Edit -->
<div class="modal fade" id="shopsModal" tabindex="-1" aria-labelledby="shopsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shopModalLabel">Edit Toko</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="shopForm">
                    <input type="hidden" id="formShopId" name="id">
                    <div class="mb-3">
                        <label for="formShopName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formShopName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formShopAddress" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="formShopAddress" name="address" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveShopBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="shopsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ShopModalLabel">Tambah Toko</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createShopForm">
                    <div class="mb-3">
                        <label for="formShopName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formShopName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formShopAddress" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="formShopAddress" name="address" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createShopBtn">Save</button>
            </div>
        </div>
    </div>
</div>

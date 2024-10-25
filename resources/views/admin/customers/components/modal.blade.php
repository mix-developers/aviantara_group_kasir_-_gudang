<!-- Modal for Create and Edit -->
<div class="modal fade" id="customersModal" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="userForm">
                    <input type="hidden" id="formCustomerId" name="id">
                    <div class="mb-3">
                        <label for="formCustomerName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formCustomerName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerPhone" class="form-label">No Hp</label>
                        <input type="text" class="form-control" id="formCustomerPhone" name="phone" value="+62"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerNik" class="form-label">NIK (opsional)</label>
                        <input type="text" class="form-control" id="formCustomerNik" name="nik" value="0">
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
                <button type="button" class="btn btn-primary" id="saveCustomerBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createUserForm">
                    <div class="mb-3">
                        <label for="formCustomerName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formCreateCustomerName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerPhone" class="form-label">No Hp</label>
                        <input type="text" class="form-control" id="formCreateCustomerPhone" name="phone"
                            value="+62" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerNik" class="form-label">NIK (opsional)</label>
                        <input type="text" class="form-control" id="formCreateCustomerNik" name="nik"
                            value="0">
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerAddressHome" class="form-label">Alamat Rumah</label>
                        <input type="text" class="form-control" id="formCreateCustomerAddressHome"
                            name="address_home" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerAddressCompany" class="form-label">Alamat Usaha</label>
                        <input type="text" class="form-control" id="formCreateCustomerAddressCompany"
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

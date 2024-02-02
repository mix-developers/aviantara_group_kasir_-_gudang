<!-- Modal for Create and Edit -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1" aria-labelledby="paymentMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="userForm">
                    <input type="hidden" id="formPaymentMethodId" name="id">
                    <div class="mb-3">
                        <label for="formPaymentMethodMethod" class="form-label">metode Pembayaran</label>
                        <input type="text" class="form-control" id="formPaymentMethodMethod" name="method" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savepaymentMethodBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PaymentMethodsModalLabel">Tambah Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createPaymentForm">
                    <input type="hidden" name="id_order_wirehouse" id="idOrderWirehouse">
                    <div class="mb-3" id="selectPaymentMethod">
                        <div class="form-check form-check-inline mt-3">

                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formPaymentMethodMethod" class="form-label">Total yang di bayarkan</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="formCreateStokUnit">Rp</span>
                            <input type="number" class="form-control" id="paid" name="paid">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createPaymentBtn">Save</button>
            </div>
        </div>
    </div>
</div>

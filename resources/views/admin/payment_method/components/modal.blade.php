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
                <h5 class="modal-title" id="PaymentMethodsModalLabel">Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createPaymentMethodForm">
                    <div class="mb-3">
                        <label for="formPaymentMethodMethod" class="form-label">metode Pembayaran</label>
                        <input type="text" class="form-control" id="formCreatePaymentMethodMethod" name="method"
                            required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createPaymentMethodBtn">Save</button>
            </div>
        </div>
    </div>
</div>

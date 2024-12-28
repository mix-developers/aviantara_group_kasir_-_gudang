<!-- Modal for Create and Edit -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="paymentMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Penjadwalan Opname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="schedulaOpnameForm">
                    <input type="hidden" id="idWirehouse" name="id_wirehouse">
                    <div class="mb-3">
                        <label for="formPaymentMethodMethod" class="form-label">Pilih Tanggal</label>
                        <select class="form-select" name="date_schedule">
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createScheduleBtn">
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createScheduleBtnSpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

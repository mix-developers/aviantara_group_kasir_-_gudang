<!-- Modal for Create and Edit -->
<div class="modal fade" id="pricesModal" tabindex="-1" aria-labelledby="shopsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shopModalLabel">Update Harga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="priceForm">
                    <input type="hidden" id="formIdProduct" name="id_product">
                    <div class="mb-3" id="descriptionStok">
                    </div>
                    <div class="mb-3">
                        <label for="formPriceGrosir" class="form-label">Harga Grosir</label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="formPriceGrosir" name="price_grosir"
                                required>
                            <span class="input-group-text" id="formPriceGrosirUnit"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePriceBtn">Save</button>
            </div>
        </div>
    </div>
</div>

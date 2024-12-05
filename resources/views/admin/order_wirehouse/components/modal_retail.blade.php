<div class="modal fade" id="create_retail" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel_retail"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border: solid rgb(255, 200, 0) 5px;">
            <div class="modal-header">
                <h5 class="modal-title" id="PaymentMethodsModalLabel_retail">Tambah Pesanan Eceran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createOrderForm_retail">
                    <input type="hidden" name="purchase_type" value="Retail">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary select-customer-retail mb-3"><i
                                class="bx bx-check"></i> Pilih Pelanggan</button>
                        <button type="button" class="btn btn-warning create-customer mb-3"><i class="bx bx-plus"></i>
                            Tambah Pelanggan</button>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3" id="descriptionCreateOrder_retail">
                                <div class="alert alert-danger alert-dismissible my-4" role="alert">
                                    <span>*Silahkan pilih pelanggan terlebih dahulu</span>
                                </div>
                            </div>
                            <input type="hidden" name="id_customer" id="formCreateCustomerId_retail"
                                name="id_customer">
                            <!-- Additional fields for retail -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="formCreateDelivery_retail"
                                        name="delivery">
                                    <label class="form-check-label" for="formCreateDelivery_retail">Pengantaran</label>
                                </div>
                                <small class="text-muted">*Aktifkan jika barang akan diantar ke pelanggan</small>
                            </div>
                            <div class="p-2"
                                style="background-color:rgba(254, 237, 195, 0.825); border-radius:10px;">
                                <div class="mb-3">
                                    <label for="selectOrderDiscount_retail">Pilih Metode Diskon</label>
                                    <select class="form-select" id="selectOrderDiscount_retail">
                                        <option value="persen">Persen (%)</option>
                                        <option value="rupiah">Rupiah (Rp)</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="divOrderDiscountPersen_retail">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="discount"
                                            id="orderDiscountPersen_retail" placeholder="Masukkan diskon dalam %">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>

                                <div class="mb-3 d-none" id="divOrderDiscountRupiah_retail">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" name="discount_rupiah"
                                            id="orderDiscountRupiah_retail" placeholder="Masukkan diskon dalam Rp">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="formProductIdWirehouse_retail" class="form-label">Pilih Gudang <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="formProductIdWirehouseCreate_retail" name="id_wirehouse"
                                    required>
                                </select>
                            </div>

                            <div class="mb-3" style="display: none;" id="hidden1_retail">
                                <label for="formPaymentMethodMethod_retail" class="form-label">Biaya Tambahan
                                    <span class="text-muted">(jika ada)</span></label>
                                <input type="number" class="form-control" id="formCreateAdditionalFee_retail"
                                    name="additional_fee" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="formCreateDueDate_retail" class="form-label">Tanggal Jatuh Tempo <span
                                        class="text-muted">(jika ada)</span></label>
                                <input type="date" class="form-control" id="formCreateDueDate_retail"
                                    name="due_date">
                            </div>

                            <div class="mb-3" style="display: none;" id="hidden2_retail">
                                <label for="formPaymentMethodMethod_retail" class="form-label">Alamat Pengantaran
                                </label>
                                <textarea class="form-control" id="formCreateAddressDelivery_retail" name="address_delivery">-</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="formPaymentMethodMethod_retail" class="form-label">Keterangan <span
                                        class="text-muted">(jika ada)</span></label>
                                <textarea class="form-control" id="formCreateDescription_retail" name="description">-</textarea>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="p-3 border" style="border-radius: 10px;">
                                <div class="mb-3">
                                    <h4>Data Pembelian Eceran</h4>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <button type="button" class="btn btn-primary  select-product-retail"><i
                                            class="bx bx-plus"></i>
                                        <span class="d-none d-sm-inline-block"> Tambah Produk </span>
                                    </button>
                                    <p class="">
                                        Total :<br>
                                        <span class="h3">Rp <span id="totalOrder_retail"
                                                class="text-danger">0</span>
                                        </span>
                                        <input type="hidden" id="total_fee_retail" name="total_fee">
                                    </p>
                                </div>

                                <div class="my-2">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Nama Produk</th>
                                                    <th style="width:100px;">Jumlah</th>
                                                    <th>Kadaluarsa</th>
                                                    <th>Sub Total</th>
                                                    <th style="width: 10px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableProductList_retail">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="resetOrderBtn_retail">Reset Form</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createOrderBtn_retail" disabled>
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createOrderBtnSpinner_retail" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerSelectionModalRetail" tabindex="-1"
    aria-labelledby="productSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-left:5px; padding-right:5px;">
                <div class="table-responsive">

                    <table id="customerSelectionTableRetail" class="table table-hover display table-sm">
                        <thead>
                            <tr>
                                <th style="width: 5px;">ID</th>
                                <th>Nama</th>
                                <th>No Hp</th>
                                <th>Alamat Rumah</th>
                                <th>Alamat Usaha</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md selectCustomerRetail">Pilih</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="productSelectionModalRetail" tabindex="-1" aria-labelledby="productSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-left:5px; padding-right:5px;">
                <div class="table-responsive">
                    <table id="productSelectionTableRetail" class="table table-hover display table-sm">
                        <thead>
                            <tr>
                                <th style="width: 5px;">ID</th>
                                <th>Nama Produk</th>
                                <th>Barcode</th>
                                <th>Harga Eceran</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md selectProductRetail">Pilih</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create-payment_retail" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PaymentMethodsModalLabel">Pembayaran</h5>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createPaymentFormRetail">
                    <div class="my-3">
                        <h5>Total Tagihan :</h5>
                        <h1 class="text-danger">Rp <span id="payment-tagihan-retail">0</span> </h1>
                    </div>
                    <hr>
                    <div class="table-responsive p-2 " style="background-color: rgba(255, 242, 208, 0.794);">
                        <table id="orderListTableRetail" class="table table-hover display table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th style="width:100px;">Jumlah</th>
                                    <th>Kadaluarsa</th>
                                    <th>Sub Total</th>
                                    <th style="width: 10px;"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <hr>
                    <input type="hidden" name="id_order_wirehouse" id="idOrderWirehouseRetail">
                    <div class="mb-3" id="selectPaymentMethodRetail">
                        <div class="form-check form-check-inline mt-3"></div>
                    </div>
                    <div class="mb-3">
                        <label for="formPaymentMethodMethod" class="form-label">Total yang di bayarkan</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="formCreateStokUnit">Rp</span>
                            <input type="number" class="form-control" id="paid-retail" name="paid"
                                onchange="calculateChangeRetail()" oninput="calculateChangeRetail()">
                        </div>
                    </div>
                    <hr>
                    <div class="my-3">
                        <h5>Kembalian :</h5>
                        <h3 class="text-primary">Rp <span id="payment-kembalian-retail">0</span> </h3>
                    </div>

                    <script>
                        function calculateChangeRetail() {
                            // Ambil nilai total tagihan
                            var totalTagihanRetail = parseFloat(document.getElementById('payment-tagihan-retail').innerText.replace('.', '')
                                .replace(',',
                                    '.')) || 0;

                            // Ambil nilai yang dibayarkan
                            var paidRetail = parseFloat(document.getElementById('paid-retail').value) || 0;

                            // Hitung kembalian
                            var changeRetail = paidRetail - totalTagihanRetail;

                            // Tampilkan kembalian, pastikan tidak negatif
                            document.getElementById('payment-kembalian-retail').innerText = changeRetail >= 0 ? changeRetail.toLocaleString(
                                    'id-ID') :
                                '0';
                        }
                    </script>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="createPaymentBtn_retail">
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createPaymentBtnSpinner_retail" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save and Print
                </button>
                <button type="button" class="btn btn-warning" id="createPaymentBtnNoPrint_retail">
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createPaymentBtnNoPrintSpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save Only
                </button>
                <br>
                <small class="text-danger" style="font-size: 8px;">*Save Only : Menyimpan tanpa print | Save and
                    Print
                    : Penyimpan dan print nota/invoice</small>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="discountProductRetail" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-primary text-white " style=" border: 2px solid white;">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body text-center">
                <form id="discountFormRetail">
                    <input type="hidden" id="discountProductIdRetail" name="id">
                    <input type="hidden" id="hargaSemulaRetail" name="harga_semula">
                    <input type="hidden" id="hargaSemulaItemRetail" name="harga_semula_item">
                    <h3 class="mb-3 fw-bold text-white">Discount : <span id="discountNameProductRetail"></span>
                    </h3>
                    <hr>
                    <div class="mb-3">
                        <label>Pilih Metode Diskon</label>
                        <select class="form-select" id="selectMethodDiscountRetail">
                            <option value="persen">Persen (%)</option>
                            <option value="rupiah">Rupiah (Rp)</option>
                        </select>
                    </div>
                    {{-- ini persentase --}}
                    <div class="mb-3" id="discountPersenRetail">
                        <div class="input-group">
                            <input type="number" class="form-control" name="discount_persen"
                                id="discountProductPersenRetail" value="0">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    {{-- ini rupiah --}}
                    <div class="mb-3" id="discountRupiahRetail" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control discountProductRupiah" name="discount_rupiah"
                                value="0" id="discountProductRupiahRetail">
                        </div>
                    </div>
                </form>
                <hr>
                <button type="button" class="btn btn-danger discountBatalRetail">Batalkan</button>
                <button type="button" class="btn btn-light" id="applyDiscountButtonRetail">
                    <div class="spinner-border spinner-border-sm text-prmary" role="status"
                        id="applyDiscountButtonSpinnerRetail" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

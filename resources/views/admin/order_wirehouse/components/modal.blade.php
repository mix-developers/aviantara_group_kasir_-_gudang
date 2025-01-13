<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PaymentMethodsModalLabel">Update Pesanan : <span id="EditInvoice"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="editOrderForm">
                    <div class="row">
                        {{-- order identity --}}
                        <div class="col-12">
                            <div class="list-group mb-3"><a href="javascript:void(0);"
                                    class="list-group-item list-group-item-action">
                                    <strong>Nama : </strong><span id="namaEditCustomer"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item list-group-item-action ">
                                    <strong>No. HP : </strong> <span id="namaEditNoHp"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item list-group-item-action ">
                                    <strong>Alamat Rumah : </strong> <span id="namaEditAddress"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item list-group-item-action ">
                                    <strong>Alamat Usaha : </strong> <span id="namaEditAddressCompany"></span>
                                </a>
                            </div>
                            <input type="hidden" name="id" id="formEditId">
                            <input type="hidden" name="id_customer" id="formEditCustomerId">
                            <div class="mb-3">
                                <div class="form-check form-switch  ">
                                    <input class="form-check-input" type="checkbox" id="formEditDelivery"
                                        name="delivery">
                                    <label class="form-check-label" for="formCreateDelivery">Pengantaran
                                    </label>
                                </div>
                                <small class="text-muted">*Aktifkan jika barang akan diantar ke pelanggan</small>
                            </div>
                            <div class="mb-3">
                                <label>Diskon</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="discount" id="formEditDiscount">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="formProductIdWirehouse" class="form-label">Pilih Gudang <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="formProductIdWirehouse" name="id_wirehouse" required>
                                </select>
                            </div>
                            <div class="mb-3" style="display: none;" id="hidden1">
                                <label for="formPaymentMethodMethod" class="form-label">Biaya Tambahan <span
                                        class="text-muted">(jika
                                        ada)</span></label>
                                <input type="number" class="form-control" id="formEditAdditionalFee"
                                    name="additional_fee" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="formEditDueDate" class="form-label">Tanggal Jatuh Tempo <span
                                        class="text-muted">(jika
                                        ada)</span></label>
                                <input type="date" class="form-control" id="formEditDueDate" name="due_date">
                            </div>
                            <div class="mb-3" style="display: none;" id="hidden2">
                                <label for="formPaymentMethodMethod" class="form-label">Alamat Pengantaran </label>
                                <textarea class="form-control" id="formEditAddressDelivery" name="address_delivery">-</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="formPaymentMethodMethod" class="form-label">Keterangan <span
                                        class="text-muted">(jika
                                        ada)</span></label>
                                <textarea class="form-control" id="formEditDescription" name="description">-</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveOrderBtn">
                    <div class="spinner-border spinner-border-sm text-white" role="status" id="saveOrderBtnSpinner"
                        style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PaymentMethodsModalLabel">Tambah Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createOrderForm">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary select-customer mb-3"><i
                                class="bx bx-check"></i>
                            Pilih
                            Pelanggan</button>
                        <button type="button" class="btn btn-warning create-customer mb-3"><i
                                class="bx bx-plus"></i>
                            Tambah
                            Pelanggan</button>
                    </div>
                    <div class="row">
                        {{-- order identity --}}
                        <div class="col-lg-4 ">
                            <div class="mb-3" id="descriptionCreateOrder">
                                <div class="alert alert-danger alert-dismissible my-4" role="alert">
                                    <span>*Silahkan pilih pelanggan terlebih dahulu</span>
                                </div>
                            </div>
                            <input type="hidden" name="id_customer" id="formCreateCustomerId" name="id_customer">
                            <div class="mb-3">
                                <div class="form-check form-switch  ">
                                    <input class="form-check-input" type="checkbox" id="formCreateDelivery"
                                        name="delivery">
                                    <label class="form-check-label" for="formCreateDelivery">Pengantaran
                                    </label>
                                </div>
                                <small class="text-muted">*Aktifkan jika barang akan diantar ke pelanggan</small>
                            </div>
                            <div class="p-2 "
                                style="background-color:rgba(254, 237, 195, 0.825); border-radius:10px;">

                                <div class="mb-3">
                                    <label>Pilih Metode Diskon</label>
                                    <select class="form-select" id="selectOrderDiscount">
                                        <option value="persen">Persen (%)</option>
                                        <option value="rupiah">Rupiah (Rp)</option>
                                    </select>
                                </div>
                                {{-- ini persentase --}}
                                <div class="mb-3" id="divOrderDiscountPersen">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="discount"
                                            id="orderDiscountPersen">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                {{-- ini rupiah --}}
                                <div class="mb-3" id="divOrderDiscountRupiah" style="display: none;">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control"
                                            name="discount_rupiah"id="orderDiscountRupiah">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="formProductIdWirehouse" class="form-label">Pilih Gudang <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="formProductIdWirehouseCreate" name="id_wirehouse"
                                    required>
                                </select>
                            </div>
                            <div class="mb-3" style="display: none;" id="hidden1">
                                <label for="formPaymentMethodMethod" class="form-label">Biaya Tambahan <span
                                        class="text-muted">(jika
                                        ada)</span></label>
                                <input type="number" class="form-control" id="formCreateAdditionalFee"
                                    name="additional_fee" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="formCreateDueDate" class="form-label">Tanggal Jatuh Tempo <span
                                        class="text-muted">(jika
                                        ada)</span></label>
                                <input type="date" class="form-control" id="formCreateDueDate" name="due_date">
                            </div>
                            <div class="mb-3" style="display: none;" id="hidden2">
                                <label for="formPaymentMethodMethod" class="form-label">Alamat Pengantaran </label>
                                <textarea class="form-control" id="formCreateAddressDelivery" name="address_delivery">-</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="formPaymentMethodMethod" class="form-label">Keterangan <span
                                        class="text-muted">(jika
                                        ada)</span></label>
                                <textarea class="form-control" id="formCreateDescription" name="description">-</textarea>
                            </div>
                        </div>
                        {{-- order items --}}
                        <div class="col-lg-8">
                            <div class="p-3 border" style="border-radius: 10px;">
                                <div class="mb-3">
                                    <h4>Data Pembelian</h4>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <button type="button" class="btn btn-primary  select-product"><i
                                            class="bx bx-plus"></i>
                                        <span class="d-none d-sm-inline-block">
                                            Tambah Produk
                                        </span>
                                    </button>
                                    <p class="">
                                        Total :<br>
                                        <span class="h3">Rp <span id="totalOrder"
                                                class="text-danger">0</span></span>
                                        <input type="hidden" id="total_fee" name="total_fee">
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
                                            <tbody id="tableProductList">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- <div class="form-group my-2" id="form-container">
                                    <div class="d-flex">
                                        <input type="text" name="title[]" placeholder="Judul"
                                            class="form-control mx-2" style="width: 200px;">
                                        <textarea name="description[]" placeholder="Isi" class="form-control mx-2" rows="1"></textarea>
                                        <button type="button" class="btn btn-primary add-button"><i
                                                class="bx bx-plus"></i></button>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="resetOrderBtn">Reset Form</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createOrderBtn" disabled>
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createOrderBtnSpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create-customer" tabindex="-1" aria-labelledby="customersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createUserForm">
                    <div class="mb-3">
                        <label for="formCustomerName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="formCustomerName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formCustomerPhone" class="form-label">No Hp</label>
                        <input type="number" class="form-control" id="formCustomerPhone" name="phone" required
                            value="+62">
                    </div>
                    <div class="mb-3">
                        <label for="formCustomer" class="form-label">NIK (opsional)</label>
                        <input type="number" class="form-control" id="formCustomerNik" name="nik"
                            value="0">
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
                <button type="button" class="btn btn-primary" id="createCustomerBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-left:5px; padding-right:5px;">
                <div class="table-responsive">

                    <table id="customerSelectionTable" class="table table-hover display table-sm">
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
                <button type="button" class="btn btn-primary btn-md selectCustomer">Pilih</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-left:5px; padding-right:5px;">
                <div class="table-responsive">
                    <table id="productSelectionTable" class="table table-hover display table-sm">
                        <thead>
                            <tr>
                                <th style="width: 5px;">ID</th>
                                <th>Nama Produk</th>
                                <th>Barcode</th>
                                <th>Harga</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md selectProduct">Pilih</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create-payment" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PaymentMethodsModalLabel">Pembayaran</h5>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createPaymentForm">
                    <div class="my-3">
                        <h5>Total Tagihan :</h5>
                        <h1 class="text-danger">Rp <span id="payment-tagihan">0</span> </h1>
                    </div>
                    <hr>
                    <div class="table-responsive p-2 " style="background-color: rgba(255, 242, 208, 0.794);">
                        <table id="orderListTable" class="table table-hover display table-sm">
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
                    <input type="hidden" name="id_order_wirehouse" id="idOrderWirehouse">
                    <div class="mb-3" id="selectPaymentMethod">
                        <div class="form-check form-check-inline mt-3"></div>
                    </div>
                    <div class="mb-3">
                        <label for="formPaymentMethodMethod" class="form-label">Total yang di bayarkan</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="formCreateStokUnit">Rp</span>
                            <input type="number" class="form-control" id="paid" name="paid" value="0"
                                min="0" onchange="calculateChange()" oninput="calculateChange()">
                        </div>
                    </div>
                    <hr>
                    <div class="my-3">
                        <h5>Kembalian :</h5>
                        <h3 class="text-primary">Rp <span id="payment-kembalian">0</span> </h3>
                    </div>

                    <script>
                        function calculateChange() {
                            // Ambil nilai total tagihan
                            var totalTagihan = parseFloat(document.getElementById('payment-tagihan').innerText.replace('.', '').replace(',',
                                '.')) || 0;

                            // Ambil nilai yang dibayarkan
                            var paid = parseFloat(document.getElementById('paid').value) || 0;

                            // Hitung kembalian
                            var change = paid - totalTagihan;

                            // Tampilkan kembalian, pastikan tidak negatif
                            document.getElementById('payment-kembalian').innerText = change >= 0 ? change.toLocaleString('id-ID') : '0';
                        }
                    </script>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="createPaymentBtn">
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createPaymentBtnSpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save and Print
                </button>
                <button type="button" class="btn btn-warning" id="createPaymentBtnNoPrint">
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                        id="createPaymentBtnNoPrintSpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Save Only
                </button>
                <br>
                <small class="text-danger" style="font-size: 8px;">*Save Only : Menyimpan tanpa print | Save and Print
                    : Penyimpan dan print nota/invoice</small>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="discountProduct" tabindex="-1" aria-labelledby="PaymentMethodsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-primary text-white " style=" border: 2px solid white;">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body text-center">
                <form id="discountForm">
                    <input type="hidden" id="discountProductId" name="id">
                    <input type="hidden" id="hargaSemula" name="harga_semula">
                    <input type="hidden" id="hargaSemulaItem" name="harga_semula_item">
                    <h3 class="mb-3 fw-bold text-white">Discount : <span id="discountNameProduct"></span></h3>
                    <hr>
                    <div class="mb-3">
                        <label>Pilih Metode Diskon</label>
                        <select class="form-select" id="selectMethodDiscount">
                            <option value="persen">Persen (%)</option>
                            <option value="rupiah">Rupiah (Rp)</option>
                        </select>
                    </div>
                    {{-- ini persentase --}}
                    <div class="mb-3" id="discountPersen">
                        <div class="input-group">
                            <input type="number" class="form-control" name="discount_persen"
                                id="discountProductPersen" value="0">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    {{-- ini rupiah --}}
                    <div class="mb-3" id="discountRupiah" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control discountProductRupiah" name="discount_rupiah"
                                value="0" id="discountProductRupiah">
                        </div>
                    </div>
                </form>
                <hr>
                <button type="button" class="btn btn-danger discountBatal">Batalkan</button>
                <button type="button" class="btn btn-light" id="applyDiscountButton">
                    <div class="spinner-border spinner-border-sm text-prmary" role="status"
                        id="applyDiscountButtonSpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

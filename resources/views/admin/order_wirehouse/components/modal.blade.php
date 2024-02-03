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
                        <button type="button" class="btn btn-primary select-customer"><i class="bx bx-check"></i>
                            Pilih
                            Pelanggan</button>
                        <button type="button" class="btn btn-warning create-customer"><i class="bx bx-plus"></i>
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
                                        Tambah Produk</button>
                                    <p class="">
                                        Total :
                                        <span class="h3">Rp <span id="totalOrder"
                                                class="text-danger">0</span></span>
                                        <input type="hidden" id="total_fee" name="total_fee">
                                    </p>
                                </div>
                                <div class="my-2">
                                    <small class="text-danger"><i class="bx bx-sm bx-info-circle"> </i> Mohon
                                        perhatikan
                                        stok
                                        yang
                                        tersedia</small>
                                </div>
                                <div class="my-2">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createOrderBtn" disabled>Save</button>
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
                        <input type="number" class="form-control" id="formCustomerPhone" name="phone" required>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md selectProduct">Pilih</button>
            </div>
        </div>
    </div>
</div>

@extends('layouts.backend.admin_no_navbar')

@section('content')
    <div class="mx-2">
        <div class="row">
            {{-- //produk --}}
            <div class="col-lg-8">
                <div class="row justify-content-between">
                    <div class="col">
                        <span class="h2 text-danger">{{ Auth::user()->name }}</span><br><small>Kasir Toko</small>
                    </div>
                    <div class="col text-end">
                        <span id="time" class="text-danger h2"></span><br>
                        <small>{{ date('d F Y') }}</small>
                    </div>
                    <hr class="my-2">
                </div>
                <div class="d-flex mb-3">
                    <button class="btn btn-primary mx-2 px-1" style="width: 200px;" data-bs-toggle="modal"
                        data-bs-target="#modalCariBarang">
                        <i class='bx bx-search'></i>
                        <span class="d-none d-sm-inline-block">Cari Barang</span>
                    </button>
                    <input type="search" class="form-control mx-2" id="barcodeInput" placeholder="Scan Barcode" autofocus>
                    <input type="number" class="form-control mx-2" id="quantityInput" value="1" style="width: 100px;">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalRiwayatPenjualan"><i class="bx bx-notepad"></i></button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Daftar Pembelian :</strong>
                    <div class="d-flex">
                        <span style="cursor: pointer;">
                            <i class="bx bx-trash p-2  text-danger" style="border-radius: 10px;"></i>
                        </span>
                        <button id="resetPurchaseList" class="btn btn-danger text-white btn-sm p-2">
                            Batalkan Semua Pembelian
                        </button>
                    </div>
                </div>
                <div class="d-flex my-3 p-2 justify-content-between bg-white align-items-center fw-bold text-primary shadow-sm text-uppercase"
                    style="border-radius: 10px;">
                    <span>Nama Barang</span>
                    <span>Jumlah</span>
                    <span>Subtotal</span>
                    <span class="d-inline-block d-lg-none">
                        <i class="bx bx-money"></i>
                    </span>
                    <span class="d-none d-lg-inline-block">
                        <i class="bx bx-money"></i> Discount
                    </span>
                    <span style="cursor: pointer;">
                        <i class="bx bx-trash p-2  text-danger" style="border-radius: 10px;"></i>
                    </span>
                </div>
                <div id="purchaseList" style="max-height: 350px; overflow-y: auto; margin-bottom:10px;"></div>
                <hr>
            </div>
            {{-- rincian --}}
            <div class="col-lg-4 ">
                <div class="bg-white p-3 shadow-sm" style="border-radius:20px; ">
                    <div class="card bg-primary text-white p-0 mb-3">
                        <div class="card-body p-2">
                            <span class="fw-bold" style="font-size: 12px;">TOTAL</span><br>
                            <center>
                                <span id="totalPrice" class="h1 fw-bold text-white">Rp 0</span>
                            </center>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <span class="fw-bold">Metode Pembayaran:</span>
                        <div class="row g-2 mt-2">
                            @php
                                $paymentMethods = App\Models\PaymentMethod::where('enabled', 1)->get();
                            @endphp
                            @foreach ($paymentMethods as $index => $item)
                                <div class="col-6 col-md-4">
                                    <input type="radio" name="paymentMethod" id="paymentMethod{{ $index }}"
                                        value="{{ $item->id }}" data-name="{{ $item->method }}" class="btn-check"
                                        {{ $index === 0 ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100" for="paymentMethod{{ $index }}"
                                        style="font-size: 11px;">
                                        {{ $item->method }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <span class="fw-bold">Dibayarkan :</span>
                        <div class="input-group">
                            <span class="input-group-text bg-warning text-black">Rp</span>
                            <input type="text" class="form-control form-control-lg"
                                placeholder="Dibayarkan, contoh: 10000" id="dibayarkan">
                        </div>
                        <small>(Perbarui jika jumlah tidak sesuai)</small>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-lg btn-danger d-block w-100 fw-bold px-1" onclick="printStruk()"><i
                                class="bx bx-md bx-save"></i>
                            <span id="loadingPrint" class="d-none">Memproses Transaksi...</span>
                            <span id="spanPrint">Pembayaran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal --}}
    @include('admin.shop_orders.components.modal')
    {{-- custom alert --}}
    @include('admin.shop_orders.components.alert')
    {{-- cetak struct --}}
    @include('admin.shop_orders.components.struk')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            let onConfirmYes = null;
            $('.modal').each(function() {
                new bootstrap.Modal(this);
            });
            // Reset daftar pembelian
            document.getElementById('resetPurchaseList').addEventListener('click', function() {
                showCustomConfirm(
                    "Apakah Anda yakin ingin menghapus semua item dari daftar pembelian?",
                    function() {
                        document.getElementById('purchaseList').innerHTML = '';
                        updateTotalPrice();
                    }
                );
            });

            // Inisialisasi DataTable
            let table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('shop-products.list') }}",
                columns: [{
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-success btn-sm pilih-produk" 
                                data-barcode="${row.barcode}" 
                                data-id-product="${row.id}" 
                                data-name="${row.name}" 
                                data-price-retail="${row.price_retail ?? 0}" 
                                onclick="addToPurchaseList('${row.name}', '${row.barcode}', 1, ${row.price_retail ?? 0},${row.stok},${row.id})">
                                <i class="bx bx-check"></i>
                            </button>`;
                        }
                    },
                    {
                        data: null,
                        name: 'name',
                        render: function(data, type, row) {
                            return `
                                <div>
                                    <strong>${row.name}</strong><br>
                                    <small>${row.barcode}</small>
                                </div>
                            `;
                        }
                    }, {
                        data: 'price_retail',
                        name: 'price_retail'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },

                ]
            });
            let orderTable = $('#historyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('shop-order-datatable') }}",
                columns: [{
                        data: null,
                        render: function(data, type, row) {
                            return `
                                    <button 
                                        onclick="printStrukUlang(this)" 
                                        class="btn btn-sm btn-primary"
                                        data-id-order="${row.id}" 
                                        data-id-invoice="${row.no_invoice}" 
                                        data-id-total="${row.total_fee}"
                                        data-id-paid="${row.payment_received}"
                                        data-id-change="${row.change}"
                                        data-id-metode="${row.order_shop_payment[0]?.payment_method?.method ?? '-'}"
                                        >
                                        <i class="bx bx-printer"></i>
                                    </button>
                                `;
                        }
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'no_invoice',
                        name: 'no_invoice'
                    },
                    {
                        data: 'total_fee',
                        name: 'total_fee'
                    },
                    {
                        data: 'payment_received',
                        name: 'payment_received'
                    },
                    {
                        data: 'change',
                        name: 'change'
                    },
                ]
            });

            // Pencarian berdasarkan barcode
            document.getElementById('barcodeInput').addEventListener('change', function() {
                let barcode = this.value.trim();
                let quantity = parseInt(document.getElementById('quantityInput').value) || 1;

                if (barcode === '' || quantity < 1) return;

                fetch(`/search-by-barcode-shop?barcode=${barcode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            addToPurchaseList(data.data.name, barcode, quantity, data.data
                                .price_retail, data.stok, data.data.id);
                                // console.log(data);
                                // console.log("id product"+data.data.id);
                        } else {
                            showCustomAlert('Produk tidak ditemukan');
                        }
                    })
                    .catch(error => console.error('Error:', error));

                this.value = ''; // Hapus input setelah scan
            });
        });

        function addToPurchaseList(productName, barcode, quantity, priceRetail, stock, idProduct) {
            // console.log("Product Name:", productName);
            // console.log("id Product:", idProduct);
            let purchaseList = document.getElementById('purchaseList');
            let items = purchaseList.querySelectorAll('.purchase-item');
            let found = false;

            items.forEach(item => {
                let itemBarcode = item.getAttribute('data-barcode');
                let quantitySpan = item.querySelector('.item-quantity');
                let priceSpan = item.querySelector('.item-price');

                if (itemBarcode === barcode) {
                    let currentQuantity = parseInt(quantitySpan.getAttribute('data-quantity'));
                    let newQuantity = currentQuantity + quantity;

                    quantitySpan.setAttribute('data-quantity', newQuantity);
                    quantitySpan.textContent = newQuantity;

                    let totalPrice = newQuantity * priceRetail;
                    priceSpan.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;

                    found = true;
                }
            });

            if (!found) {
                if (stock < quantity) {
                    showCustomAlert('Stok tidak mencukupi');
                    return;
                }
                let itemNumber = document.querySelectorAll('.purchase-item').length + 1;
                let itemHTML = `
                <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm purchase-item"
                    data-barcode="${barcode}" style="border-radius: 10px;" data-id-product="${idProduct}">
                    <span class="product-name">${productName}</span>
                    <div class="d-flex align-items-center">
                        <button onclick="updateQty('${barcode}', -1)" class="btn btn-sm btn-outline-light p-1">-</button>
                        <span class="item-quantity p-1" data-quantity="${quantity}">${quantity}</span>
                        <button onclick="updateQty('${barcode}', 1)" class="btn btn-sm btn-outline-light p-1">+</button>
                    </div>
                    <span class="item-price" data-original-price="${quantity * priceRetail}">
                        Rp ${(quantity * priceRetail).toLocaleString('id-ID')}
                    </span>
                    <button class="btn btn-sm p-1" style="border-radius: 10px;" onclick="discountItem(this);" data-barcode="${barcode}" >
                        <i class="bx bx-money text-white"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light" style="border-radius: 10px;" onclick="removeItem(this);">
                        <i class="bx bx-trash "></i>
                    </button>
                </div>
                
                 <!-- Modal Diskon -->
                <div class="modal fade" id="modalDiscount-${barcode}" tabindex="-1" aria-labelledby="modalDiscountLabel" aria-hidden="true"
                    data-backdrop="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDiscountLabel">Terapkan Diskon</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="discountBarcode">
                                <div class="p-2 border border-info rounded">
                                    <p><strong>Nama Barang : </strong><span id="discountItemName"></span></p>
                                    <p><strong>Harga Asli : </strong>Rp <span id="discountOriginalPrice"></span></p>
                                </div>
                                <div class="mb-3">
                                    <label for="discountAmount" class="form-label">Masukkan Diskon (Rp)</label>
                                    <input type="number" id="discountAmount-${barcode}" class="form-control" min="0"
                                        placeholder="contoh : 1000">
                                </div>
                                <button class="btn btn-primary w-100" onclick="applyDiscount('${barcode}')">Terapkan</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                purchaseList.innerHTML += itemHTML;
            }

            updateTotalPrice();
        }

        function removeItem(button) {
            button.parentElement.remove();
            updateTotalPrice();
        }

        function updateQty(barcode, delta) {
            let item = document.querySelector(`.purchase-item[data-barcode="${barcode}"]`);
            if (!item) return;

            let quantitySpan = item.querySelector('.item-quantity');
            let quantity = parseInt(quantitySpan.getAttribute('data-quantity')) + delta;
            if (quantity < 1) return;

            let priceRetail = parseInt(item.querySelector('.item-price').getAttribute('data-original-price')) / parseInt(
                quantitySpan.getAttribute('data-quantity'));
            let newPrice = quantity * priceRetail;

            quantitySpan.setAttribute('data-quantity', quantity);
            quantitySpan.textContent = quantity;
            item.querySelector('.item-price').textContent = `Rp ${newPrice.toLocaleString('id-ID')}`;
            item.querySelector('.item-price').setAttribute('data-original-price', newPrice);

            updateTotalPrice();
        }

        function updateTotalPrice() {
            let total = 0;
            document.querySelectorAll(".item-price").forEach(price => {
                total += parseInt(price.textContent.replace(/\D/g, ''));
            });
            document.getElementById("totalPrice").textContent = `Rp ${total.toLocaleString('id-ID')}`;
            // pembayaran
            let roundedTotal = Math.ceil(total / 5000) * 5000;
            document.getElementById("dibayarkan").value = roundedTotal;
        }

        function discountItem(button) {
            let item = button.closest('.purchase-item');
            let barcode = item.getAttribute('data-barcode');
            // console.log("Barcode Produk:", barcode); // Debugging

            let modalElement = document.getElementById(`modalDiscount-${barcode}`);
            if (!modalElement) {
                console.error("Modal tidak ditemukan untuk barcode:", barcode);
                return;
            }

            let itemName = item.children[1].textContent;
            let priceElement = item.querySelector('.item-price');
            let originalPrice = parseInt(priceElement.getAttribute('data-original-price') || priceElement.textContent
                .replace(/\D/g, ''));

            modalElement.querySelector('#discountItemName').textContent = itemName;
            modalElement.querySelector('#discountOriginalPrice').textContent = originalPrice.toLocaleString('id-ID');

            let modalInstance = new bootstrap.Modal(modalElement);
            modalInstance.show();
        }

        function applyDiscount(barcode) {
            let modalElement = document.getElementById(`modalDiscount-${barcode}`);
            let discountInput = modalElement.querySelector(`#discountAmount-${barcode}`);
            let discountAmount = parseInt(discountInput.value); // Default ke 0 jika kosong

            let item = document.querySelector(`.purchase-item[data-barcode="${barcode}"]`);
            if (!item) {
                alert('Item tidak ditemukan!');
                return;
            }

            let priceElement = item.querySelector('.item-price');
            let originalPrice = parseInt(priceElement.getAttribute('data-original-price'));

            // Pastikan harga asli tetap ada dan digunakan untuk perhitungan diskon
            let newPrice = originalPrice - discountAmount;
            if (newPrice < 0) newPrice = 0;

            priceElement.textContent = `Rp ${newPrice.toLocaleString('id-ID')}`;

            // Tutup modal setelah menerapkan diskon
            let modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }



            updateTotalPrice();
        }

        function showCustomAlert(message) {
            document.getElementById("customAlertMessage").textContent = message;
            document.getElementById("customAlert").classList.remove("d-none");
        }

        function closeCustomAlert() {
            document.getElementById("customAlert").classList.add("d-none");
        }


        function showCustomConfirm(message, yesCallback) {
            document.getElementById("customConfirmMessage").textContent = message;
            document.getElementById("customConfirm").classList.remove("d-none");
            onConfirmYes = yesCallback;
        }

        function confirmYes() {
            if (onConfirmYes) {
                onConfirmYes();
                onConfirmYes = null;
            }
            document.getElementById("customConfirm").classList.add("d-none");
        }

        function confirmNo() {
            document.getElementById("customConfirm").classList.add("d-none");
        }

        // print struk
        function getSelectedPaymentMethod() {
            let selected = document.querySelector('input[name="paymentMethod"]:checked');
            return selected ? selected.getAttribute('data-name') : 'Cash'; // Default ke "Tunai" jika tidak dipilih
        }

        function getSelectedPaymentMethodId() {
            const selected = document.querySelector('input[name="paymentMethod"]:checked');
            return selected ? selected.value : null;
        }
        // Fungsi untuk mencetak struk

        function printStruk() {
            // Tampilkan loading
            document.getElementById('spanPrint').classList.add('d-none');
            document.getElementById('loadingPrint').classList.remove('d-none');

            const strukItemsArea = document.getElementById('strukItems');
            strukItemsArea.innerHTML = '';

            const items = document.querySelectorAll('.purchase-item');
            let total = 0;
            let orderItems = [];

            items.forEach(item => {
                const idProduct = item.getAttribute('data-id-product');
                const name = item.querySelector('.product-name').innerText;
                // const name = item.children[1].innerText;
                const qty = parseInt(item.querySelector('.item-quantity').innerText);
                const priceText = item.querySelector('.item-price').innerText;
                const price = parseInt(priceText.replace(/\D/g, ''));

                total += price;

                // Tampilkan di struk
                strukItemsArea.innerHTML += `
                        <div style="display: flex; justify-content: space-between;">
                            <span>${qty}x ${name}</span>
                            <span>${priceText}</span>
                        </div>
                    `;

                orderItems.push({
                    id_product: parseInt(idProduct),
                    quantity: qty,
                    price: price,
                    discount: 0,
                    discount_rupiah: 0,
                    subtotal: price
                });
            });

            const dibayarkan = parseInt(document.getElementById('dibayarkan').value || 0);
            const kembali = dibayarkan - total;
            const metodePembayaran = getSelectedPaymentMethod(); // nama
            const idMetodePembayaran = getSelectedPaymentMethodId(); // id

            document.getElementById('strukMetode').textContent = metodePembayaran;
            document.getElementById('strukTotal').innerText = `Rp ${total.toLocaleString('id-ID')}`;
            document.getElementById('strukBayar').innerText = `Rp ${dibayarkan.toLocaleString('id-ID')}`;
            document.getElementById('strukKembali').innerText = `Rp ${kembali.toLocaleString('id-ID')}`;

            // Simpan data
            fetch('/shop-order/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order: {
                            id_user: {{ auth()->id() }},
                            id_shop: {{ $shop->id }},
                            no_invoice: `AVI-${Date.now()}`,
                            total_fee: total,
                            payment_received: dibayarkan,
                            change: kembali,
                            fee: 0,
                            additional_fee: 0,
                            discount: 0,
                            discount_rupiah: 0,
                            description: ''
                        },
                        items: orderItems,
                        payments: [{
                            id_payment_method: idMetodePembayaran,
                            paid: dibayarkan,
                            id_user: {{ auth()->id() }}
                        }]
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tampilkan loading
                        document.getElementById('spanPrint').classList.remove('d-none');
                        document.getElementById('loadingPrint').classList.add('d-none');
                        // Cetak setelah berhasil
                        const strukHTML = document.getElementById('strukArea').innerHTML;
                        const printWindow = window.open('', '_blank', 'width=400,height=600');

                        printWindow.document.write(`
                            <html>
                            <head>
                                <title>Struk AVI-${Date.now()}</title>
                                <style>
                                    body { font-family: monospace; font-size: 12px; padding: 10px; }
                                    h4, p, div, span, hr { margin: 0; padding: 0; }
                                    hr { margin: 5px 0; border-top: 1px dashed #000; }
                                    .line { display: flex; justify-content: space-between; }
                                </style>
                            </head>
                            <body onload="window.print(); window.close();">
                                ${strukHTML}
                            </body>
                            </html>
                        `);
                        printWindow.document.close();

                        document.getElementById('purchaseList').innerHTML = '';
                        updateTotalPrice();

                    } else {
                        showCustomAlert("Gagal menyimpan transaksi: " + data.message);
                        // Tampilkan loading
                        document.getElementById('spanPrint').classList.remove('d-none');
                        document.getElementById('loadingPrint').classList.add('d-none');

                    }
                })
                .catch(error => {
                    console.error('Gagal simpan transaksi:', error);
                    showCustomAlert("Terjadi kesalahan saat menyimpan transaksi");
                    // Tampilkan loading
                    document.getElementById('spanPrint').classList.remove('d-none');
                    document.getElementById('loadingPrint').classList.add('d-none');
                });
            $('#productTable').DataTable().ajax.reload();
            $('#historyTable').DataTable().ajax.reload();
        }

        function printStrukUlang(button) {
            // Tampilkan loading
            document.getElementById('spanPrint').classList.add('d-none');
            document.getElementById('loadingPrint').classList.remove('d-none');

            const idOrder = button.getAttribute('data-id-order');
            const noInvoice = button.getAttribute('data-id-invoice');
            const total = parseInt(button.getAttribute('data-id-total'));
            const dibayarkan = parseInt(button.getAttribute('data-id-paid'));
            const kembali = parseInt(button.getAttribute('data-id-change'));
            const metode = button.getAttribute('data-id-metode') || '-';

            // Ambil item struk dari backend
            fetch(`/shop-order/${idOrder}/items`)
                .then(res => res.json())
                .then(items => {
                    let strukItemsHTML = '';
                    items.forEach(item => {
                        strukItemsHTML += `
                    <div style="display: flex; justify-content: space-between;">
                        <span>${item.quantity}x ${item.product_name}</span>
                        <span>Rp ${item.price.toLocaleString('id-ID')}</span>
                    </div>
                `;
                    });

                    const strukHTML = `
                <center>
                    <h4>{{ env('APP_NAME') }} <br> {{ $shop->name }}</h4>
                    <p>{{ $shop->address }}</p>
                    <p>Kasir : {{ Auth::user()->name }}</p>
                    <p>${(new Date()).toLocaleString('id-ID')}</p>
                    <hr>
                </center>

                ${strukItemsHTML}

                <hr>
                <div style="display: flex; justify-content: space-between;">
                    <strong>Total:</strong>
                    <span>Rp ${total.toLocaleString('id-ID')}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Dibayar:</span>
                    <span>Rp ${dibayarkan.toLocaleString('id-ID')}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Kembalian:</span>
                    <span>Rp ${kembali.toLocaleString('id-ID')}</span>
                </div>
                <hr>
                <div style="display: flex; justify-content: space-between;">
                    <span>Metode:</span>
                    <span>${metode}</span>
                </div>
                <center>
                    <hr>
                    <p>Terima kasih!</p>
                </center>
            `;

                    const printWindow = window.open('', '_blank', 'width=400,height=600');
                    printWindow.document.write(`
                        <html>
                        <head>
                            <title>Struk ${noInvoice}</title>
                            <style>
                                body { font-family: monospace; font-size: 12px; padding: 10px; }
                                h4, p, div, span, hr { margin: 0; padding: 0; }
                                hr { margin: 5px 0; border-top: 1px dashed #000; }
                                .line { display: flex; justify-content: space-between; }
                            </style>
                        </head>
                        <body onload="window.print(); window.close();">
                            ${strukHTML}
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                })
                .catch(error => {
                    showCustomAlert("Gagal mengambil data struk.");
                    console.error(error);
                })
                .finally(() => {
                    document.getElementById('spanPrint').classList.remove('d-none');
                    document.getElementById('loadingPrint').classList.add('d-none');
                });
        }
    </script>
@endpush

<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KiosController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StokKiosController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\WirehouseController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\OrderWirehouseController;
use App\Http\Controllers\ProductDamagedController;
use App\Http\Controllers\ReportController;
use App\Models\OrderWirehouse;
use App\Models\OrderWirehouseItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages/index', [
        'product' => Product::paginate(12)
    ]);
});
Route::get('/download_all_product', function () {
    return \PDF::loadView('pages.pdf.all_product')
        ->setPaper('a4', 'portrait') // Orientasi portrait
        ->download('HARGA PRODUK AVIANTARA Berlaku ' . date('F Y') . '__' . date('dmyhis') . '.pdf'); // Nama file
});
Route::get('/get-top-selling-products', function (Request $request) {
    // Get the top 5 best-selling products
    $topSellingProducts = OrderWirehouseItem::select('order_wirehouse_items.id_product', DB::raw('count(*) as sales_count'))
        ->join('products', 'order_wirehouse_items.id_product', '=', 'products.id')
        ->groupBy('order_wirehouse_items.id_product')
        ->orderBy('sales_count', 'desc')
        ->take(8)
        ->get();

    // Optionally, you can load more details like product price, image, etc.
    $topSellingProducts->load('product'); // Eager load the product details if necessary

    // Return the HTML of the top-selling products view
    return response()->json([
        'html' => view('pages.top-selling-products', compact('topSellingProducts'))->render(),
    ]);
});
Route::get('/get-products', function (Request $request) {
    // Retrieve the search query and wirehouse filter from the request
    $search = $request->input('search');
    $wirehouseId = $request->input('wirehouse');

    // Start building the query
    $query = Product::with('wirehouse'); // Adding relation if necessary

    // Apply search filter if a search query is provided
    if ($search) {
        $query->where('name', 'like', '%' . $search . '%'); // Adjust 'name' field as needed
    }

    // Paginate the results
    $products = $query->paginate(8); // Adjust number per page as needed

    if ($request->ajax()) {
        return response()->json([
            'html' => view('pages.product-list', compact('products'))->render(),
            'pagination' => (string) $products->links('vendor.pagination.bootstrap-4') // Return pagination HTML
        ]);
    }

    return view('pages.index', compact('products'));
});
Route::get('/search', function (Request $request) {
    $search = $request->input('search');
    $wirehouse = $request->input('wirehouse');

    $product = Product::where('name', 'LIKE', '%' . $search . '%');
    if ($wirehouse != '-') {
        $product->where('id_wirehouse', $wirehouse);
    }
    return view('pages.index', [
        'product' => $product->paginate(12),
        'search' => $search,
        'wirehouse' => $wirehouse,
    ]);
});
Route::get('/invoice', function () {
    return view('pages/cek_invoice', ['title' => 'Cek Invoice']);
})->name('invoice');
Route::get('/get-invoice/{invoice}', [OrderWirehouseController::class, 'getInvoice']);

Auth::routes(['register' => false, 'reset' => false]);
Route::middleware(['auth:web', 'checkDisabled'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/dashboard2', [App\Http\Controllers\HomeController::class, 'dashboard2']);
    Route::get('/chart-paid', [App\Http\Controllers\HomeController::class, 'getChartPaid']);
    Route::get('/chart-expired', [App\Http\Controllers\HomeController::class, 'getChartExpired']);
    Route::get('/chart-order-all-wirehouse', [App\Http\Controllers\HomeController::class, 'getChartOrderAllWirehouses']);
    Route::get('/chart-payment-all-wirehouse', [App\Http\Controllers\HomeController::class, 'getChartPaymentAllWirehouses']);

    //dashboard
    Route::get('/expired-alert', [HomeController::class, 'expiredAlert']);
    Route::get('/get-stok-card', [HomeController::class, 'getStokCard']);
    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
Route::middleware(['auth:web', 'role:Gudang,Admin,Owner', 'checkDisabled'])->group(function () {
    //report
    Route::get('/report/pdf-daily', [ReportController::class, 'pdf_daily'])->name('report.pdf-daily');
    // Route::get('/report/pdf-income', [ReportController::class, 'pdf_income'])->name('report.pdf-income');
    Route::get('/report/report-payment-datatable', [PaymentMethodController::class, 'getReportPaymentsDataTable']);
    Route::get('/get_total_payment_method/{id}', [PaymentMethodController::class, 'getTotalPaymentMethod']);
    //
    Route::get('/paymentMethod/getall', [PaymentMethodController::class, 'getAll'])->name('paymentMethod.getall');
    Route::get('/order-item-datatable/{id}', [OrderWirehouseController::class, 'getOrderItemsDataTable']);
    Route::post('/discount-order-items/store', [OrderWirehouseController::class, 'store_discount']);
    //customers managemen
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/getall', [CustomerController::class, 'getAll'])->name('customers.getall');
    Route::get('/customers/getCustomer/{id}', [CustomerController::class, 'getCustomer'])->name('customers.getCustomer');
    Route::get('/customers/show/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/store',  [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/edit/{id}',  [CustomerController::class, 'edit'])->name('customers.edit');
    Route::delete('/customers/delete/{id}',  [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('/customers-datatable', [CustomerController::class, 'getCustomersDataTable']);
    Route::get('/customers-datatable-detail/{id}', [CustomerController::class, 'getCustomersDataTableDetail']);
    //product damaged managemen
    Route::get('/damageds', [ProductDamagedController::class, 'index'])->name('damageds');
    Route::get('/damageds/getall', [ProductDamagedController::class, 'getAll'])->name('damageds.getall');
    Route::get('/damageds/show/{id}', [ProductDamagedController::class, 'show'])->name('damageds.show');
    Route::post('/damageds/store',  [ProductDamagedController::class, 'store'])->name('damageds.store');
    Route::get('/damageds/edit/{id}',  [ProductDamagedController::class, 'edit'])->name('damageds.edit');
    Route::delete('/damageds/delete/{id}',  [ProductDamagedController::class, 'destroy'])->name('damageds.delete');
    Route::get('/damageds-datatable', [ProductDamagedController::class, 'getDamagedsDataTable']);
    //stok managemen
    Route::get('/products', [StokController::class, 'products'])->name('products');
    Route::get('/products/scan', [StokController::class, 'scanProduct'])->name('products.scan');
    Route::get('/products/getall', [StokController::class, 'getAllProduct'])->name('products.getall');
    Route::get('/products/show/{id}', [StokController::class, 'show_product'])->name('products.show');
    Route::get('/products-datatable', [StokController::class, 'getProductsDataTable']);
    Route::get('/product-detail-datatable/{id}', [StokController::class, 'getProductDetailDataTable']);
    Route::post('/products/store',  [StokController::class, 'store_product'])->name('products.store');
    Route::get('/products/edit/{id}',  [StokController::class, 'edit_product'])->name('products.edit');
    Route::delete('/products/delete/{id}',  [StokController::class, 'destroy_product'])->name('products.delete');
    // -----
    Route::get('/stoks-expired-date/{id}', [StokController::class, 'stokExpiredDate']);
    Route::get('/stoks', [StokController::class, 'stoks'])->name('stoks');
    Route::get('/stoks-datatable', [StokController::class, 'getStoksDataTable']);
    Route::post('/stoks/store',  [StokController::class, 'store_stok'])->name('stoks.store');
    Route::get('/stoks/edit/{id}',  [StokController::class, 'edit_stok'])->name('stoks.edit');
    Route::delete('/stoks/delete/{id}',  [StokController::class, 'destroy_stok'])->name('stoks.delete');
    //order wirehouses managemen
    Route::get('/order_wirehouses', [OrderWirehouseController::class, 'index'])->name('order_wirehouses');
    Route::get('/order_wirehouses/getall', [OrderWirehouseController::class, 'getAll'])->name('order_wirehouses.getall');
    Route::post('/order_wirehouses/update',  [OrderWirehouseController::class, 'update'])->name('order_wirehouses.update');
    Route::post('/order_wirehouses/store',  [OrderWirehouseController::class, 'store'])->name('order_wirehouses.store');
    Route::get('/order_wirehouses/print-invoice/{id}',  [OrderWirehouseController::class, 'printInvoice'])->name('order_wirehouses.print-invoice');
    Route::get('/order_wirehouses/edit/{id}',  [OrderWirehouseController::class, 'edit'])->name('order_wirehouses.edit');
    Route::delete('/order_wirehouses/delete/{id}',  [OrderWirehouseController::class, 'destroy'])->name('order_wirehouses.delete');
    Route::get('/order-wirehouses-datatable', [OrderWirehouseController::class, 'getOrderWirehousesDataTable']);
    Route::get('/order-wirehouses-item-datatable/{id}', [OrderWirehouseController::class, 'getOrderWirehouseItemDataTable']);
    Route::get('/get-order-wirehouse-items/{id}', [OrderWirehouseController::class, 'getOrderWIrehouseItems']);
    Route::get('/order_wirehouses/invoice/{invoice}',  [OrderWirehouseController::class, 'invoice'])->name('order_wirehouses.invoice');
    //order wirehouses payment managemen
    Route::get('/payments', [OrderPaymentController::class, 'index'])->name('payments');
    Route::get('/payments/getall', [OrderPaymentController::class, 'getAll'])->name('payments.getall');
    Route::post('/payments/store',  [OrderPaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/edit/{id}',  [OrderPaymentController::class, 'edit'])->name('payments.edit');
    Route::get('/payments/print-delivery/{id}',  [OrderPaymentController::class, 'printDelivery'])->name('payments.print-delivery');
    Route::delete('/payments/delete/{id}',  [OrderPaymentController::class, 'destroy'])->name('payments.delete');
    Route::get('/payment-detail-datatable/{id}', [OrderPaymentController::class, 'getPaymentDetailDataTable']);
    Route::post('/send_bill/{id}', [OrderPaymentController::class, 'send_bill'])->name('send_bill');
    Route::delete('/payments/delete/{id}', [OrderPaymentController::class, 'destroy'])->name('payments.delete');
    //prices
    Route::get('/prices-datatable', [PriceController::class, 'getPricesDataTable']);
    Route::get('/prices-order-datatable', [PriceController::class, 'getPricesOrderDataTable']);
    //wirehouse managemen
    Route::get('/wirehouses', [WirehouseController::class, 'index'])->name('wirehouses');
    Route::get('/wirehouses/show/{id}', [WirehouseController::class, 'show'])->name('wirehouses.show');
    Route::get('/wirehouse-detail-datatable/{id}', [WirehouseController::class, 'getWirehouseDetailDataTable']);
    Route::get('/wirehouse-total-product/{id}', [WirehouseController::class, 'getWirehouseTotalProduct']);
    Route::get('/wirehouses/getall', [WirehouseController::class, 'getAll'])->name('wirehouses.getall');
    Route::post('/wirehouses/store',  [WirehouseController::class, 'store'])->name('wirehouses.store');
    Route::get('/wirehouses/edit/{id}',  [WirehouseController::class, 'edit'])->name('wirehouses.edit');
    Route::delete('/wirehouses/delete/{id}',  [WirehouseController::class, 'destroy'])->name('wirehouses.delete');
    Route::get('/wirehouses-datatable', [WirehouseController::class, 'getWirehousesDataTable']);
});
Route::middleware(['auth:web', 'role:Gudang', 'checkDisabled'])->group(function () {
    Route::get('/report/report-daily', [ReportController::class, 'reportDaily'])->name('report.report-daily');
});
Route::middleware(['auth:web', 'role:Admin,Owner', 'checkDisabled'])->group(function () {

    //report managemen
    Route::get('/report/stok-wirehouse', [ReportController::class, 'stok_wirehouse'])->name('report.stok-wirehouse');
    Route::get('/report/pdf-stok-wirehouse', [ReportController::class, 'pdf_stok_wirehouse'])->name('report.pdf-stok-wirehouse');
    // Route::get('/report/report-payment-datatable', [PaymentMethodController::class, 'getReportPaymentsDataTable']);
    Route::get('/report/price', [ReportController::class, 'price'])->name('report.price');
    Route::get('/report/pdf-price', [ReportController::class, 'pdf_price'])->name('report.pdf-price');
    Route::get('/report/income', [ReportController::class, 'income'])->name('report.income');
    Route::get('/report/pdf-income', [ReportController::class, 'pdf_income'])->name('report.pdf-income');
    Route::get('/report/damaged', [ReportController::class, 'damaged'])->name('report.damaged');
    Route::get('/report/pdf-damaged', [ReportController::class, 'pdf_damaged'])->name('report.pdf-damaged');
    Route::get('/report/wirehouses', [ReportController::class, 'transactionWirehouses'])->name('report.wirehouses');
    Route::get('/report/pdf-wirehouses', [ReportController::class, 'pdf_transactionWirehouses'])->name('report.pdf-wirehouses');
    Route::get('/report/shops', [ReportController::class, 'transactionShops'])->name('report.shops');
    //user managemen
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/getall', [UserController::class, 'getAll']);
    Route::post('/users/store',  [UserController::class, 'store'])->name('users.store');
    Route::post('/users/reset', [UserController::class, 'resetPassword'])->name('users.reset');
    Route::get('/users/edit/{id}',  [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/users/delete/{id}',  [UserController::class, 'destroy'])->name('users.delete');
    Route::get('/users-datatable', [UserController::class, 'getUsersDataTable']);
    //payment method managemen
    Route::get('/paymentMethod', [PaymentMethodController::class, 'index'])->name('paymentMethod');
    // Route::get('/paymentMethod/getall', [PaymentMethodController::class, 'getAll'])->name('paymentMethod.getall');
    Route::post('/paymentMethod/store',  [PaymentMethodController::class, 'store'])->name('paymentMethod.store');
    Route::get('/paymentMethod/edit/{id}',  [PaymentMethodController::class, 'edit'])->name('paymentMethod.edit');
    Route::get('/paymentMethod/show/{id}',  [PaymentMethodController::class, 'show'])->name('paymentMethod.show');
    Route::delete('/paymentMethod/delete/{id}',  [PaymentMethodController::class, 'destroy'])->name('paymentMethod.delete');
    Route::get('/paymentMethod-datatable', [PaymentMethodController::class, 'getPaymentMethodDataTable']);
    Route::get('/paymentMethod-datatable-detail/{id}', [PaymentMethodController::class, 'getPaymentMethodDetailDataTable']);

    //product price managemen
    Route::get('/prices', [PriceController::class, 'index'])->name('prices');
    Route::get('/prices/get-not-price', [PriceController::class, 'getNotPrice'])->name('prices.get-not-price');
    Route::get('/prices/getall', [PriceController::class, 'getAll'])->name('prices.getall');
    Route::post('/prices/store',  [PriceController::class, 'store'])->name('prices.store');
    Route::get('/prices/show/{id}',  [PriceController::class, 'show'])->name('prices.show');
    Route::get('/prices/pdf/{id}',  [PriceController::class, 'pdf'])->name('prices.pdf');
    Route::get('/prices/edit/{id}',  [PriceController::class, 'edit'])->name('prices.edit');
    Route::delete('/prices/delete/{id}',  [PriceController::class, 'destroy'])->name('prices.delete');
    // Route::get('/prices-datatable', [PriceController::class, 'getPricesDataTable']);
    Route::get('/price-detail-datatable/{id}', [PriceController::class, 'getPriceDetailDataTable']);
    //shop managemen
    Route::get('/shops', [ShopController::class, 'index'])->name('shops');
    Route::get('/shops/getall', [ShopController::class, 'getAll'])->name('shops.getall');
    Route::get('/shops/show/{id}', [ShopController::class, 'show'])->name('shops.show');
    Route::post('/shops/store',  [ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/edit/{id}',  [ShopController::class, 'edit'])->name('shops.edit');
    Route::delete('/shops/delete/{id}',  [ShopController::class, 'destroy'])->name('shops.delete');
    Route::get('/shops-datatable', [ShopController::class, 'getShopsDataTable']);

    //Kios
    Route::get('/kios', [KiosController::class, 'index'])->name('kios');
});
Route::middleware(['auth:web', 'role:Kasir', 'checkDisabled'])->group(function () {
    //Kios Stok
    Route::get('/kios_stok', [StokKiosController::class, 'index'])->name('kios_stok');
    Route::get('/kios-stok-datatable', [StokKiosController::class, 'getStokKiosDataTable'])->name('kios-stok-datatable');
    Route::get('/kios_stok/getShop/{id_shop}', [StokKiosController::class, 'getShop'])->name('kios_stok.getShop');
    Route::get('/kios_stok/getall', [StokKiosController::class, 'getAll'])->name('kios_stok.getall');
    Route::get('/kios_stok/edit/{id}', [StokKiosController::class, 'edit'])->name('kios_stok.edit');
    Route::post('/kios_stok/store',  [StokKiosController::class, 'store'])->name('kios_stok.store');
    Route::post('/kios_stok/update',  [StokKiosController::class, 'update'])->name('kios_stok.update');
    Route::get('/kios_stok/search',  [StokKiosController::class, 'search'])->name('kios_stok.search');
    //transaction managemenet
    Route::get('/transaksi-kios', [TransaksiController::class, 'index'])->name('transkasi-kios');
    Route::get('/transaksi/getall', [TransaksiController::class, 'getAll'])->name('transkasi.getall');
    Route::get('/transaksi/scan', [TransaksiController::class, 'scanBarcode'])->name('transkasi.scan');
    Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transkasi.store');
});

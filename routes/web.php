<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductDamagedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WirehouseController;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/invoice', function () {
    return view('pages/cek_invoice', ['title' => 'Cek Invoice']);
})->name('invoice');

Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::middleware(['auth:web'])->group(function () {

    //dashboard
    Route::get('/expired-alert', [HomeController::class, 'expiredAlert']);
    Route::get('/get-stok-card', [HomeController::class, 'getStokCard']);
    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //stok managemen
    Route::get('/products', [StokController::class, 'products'])->name('products');
    Route::get('/products/getall', [StokController::class, 'getAllProduct'])->name('products.getall');
    Route::get('/products/show/{id}', [StokController::class, 'show_product'])->name('products.show');
    Route::get('/products-datatable', [StokController::class, 'getProductsDataTable']);
    Route::get('/product-detail-datatable/{id}', [StokController::class, 'getProductDetailDataTable']);
    Route::post('/products/store',  [StokController::class, 'store_product'])->name('products.store');
    Route::get('/products/edit/{id}',  [StokController::class, 'edit_product'])->name('products.edit');
    Route::delete('/products/delete/{id}',  [StokController::class, 'destroy_product'])->name('products.delete');
    // -----
    Route::get('/stoks', [StokController::class, 'stoks'])->name('stoks');
    Route::get('/stoks-datatable', [StokController::class, 'getStoksDataTable']);
    Route::post('/stoks/store',  [StokController::class, 'store_stok'])->name('stoks.store');
    Route::get('/stoks/edit/{id}',  [StokController::class, 'edit_stok'])->name('stoks.edit');
    Route::delete('/stoks/delete/{id}',  [StokController::class, 'destroy_stok'])->name('stoks.delete');
    //customers managemen
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/getall', [CustomerController::class, 'getAll'])->name('customers.getall');
    Route::get('/customers/show/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/store',  [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/edit/{id}',  [CustomerController::class, 'edit'])->name('customers.edit');
    Route::delete('/customers/delete/{id}',  [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('/customers-datatable', [CustomerController::class, 'getCustomersDataTable']);
    //product damaged managemen
    Route::get('/damageds', [ProductDamagedController::class, 'index'])->name('damageds');
    Route::get('/damageds/getall', [ProductDamagedController::class, 'getAll'])->name('damageds.getall');
    Route::get('/damageds/show/{id}', [ProductDamagedController::class, 'show'])->name('damageds.show');
    Route::post('/damageds/store',  [ProductDamagedController::class, 'store'])->name('damageds.store');
    Route::get('/damageds/edit/{id}',  [ProductDamagedController::class, 'edit'])->name('damageds.edit');
    Route::delete('/damageds/delete/{id}',  [ProductDamagedController::class, 'destroy'])->name('damageds.delete');
    Route::get('/damageds-datatable', [ProductDamagedController::class, 'getDamagedsDataTable']);
});
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
    //user managemen
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/getall', [UserController::class, 'getAll']);
    Route::post('/users/store',  [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}',  [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/users/delete/{id}',  [UserController::class, 'destroy'])->name('users.delete');
    Route::get('/users-datatable', [UserController::class, 'getUsersDataTable']);
    //payment method managemen
    Route::get('/paymentMethod', [PaymentMethodController::class, 'index'])->name('paymentMethod');
    Route::get('/paymentMethod/getall', [PaymentMethodController::class, 'getAll'])->name('paymentMethod.getall');
    Route::post('/paymentMethod/store',  [PaymentMethodController::class, 'store'])->name('paymentMethod.store');
    Route::get('/paymentMethod/edit/{id}',  [PaymentMethodController::class, 'edit'])->name('paymentMethod.edit');
    Route::delete('/paymentMethod/delete/{id}',  [PaymentMethodController::class, 'destroy'])->name('paymentMethod.delete');
    Route::get('/paymentMethod-datatable', [PaymentMethodController::class, 'getPaymentMethodDataTable']);
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
    //shop managemen
    Route::get('/shops', [ShopController::class, 'index'])->name('shops');
    Route::get('/shops/getall', [ShopController::class, 'getAll'])->name('shops.getall');
    Route::post('/shops/store',  [ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/edit/{id}',  [ShopController::class, 'edit'])->name('shops.edit');
    Route::delete('/shops/delete/{id}',  [ShopController::class, 'destroy'])->name('shops.delete');
    Route::get('/shops-datatable', [ShopController::class, 'getShopsDataTable']);
});

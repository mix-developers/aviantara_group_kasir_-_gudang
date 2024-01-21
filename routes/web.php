<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
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

    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //customers managemen
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::post('/customers/store',  [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/edit/{id}',  [CustomerController::class, 'edit'])->name('customers.edit');
    Route::delete('/customers/delete/{id}',  [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('/customers-datatable', [CustomerController::class, 'getCustomersDataTable']);
});
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
    //user managemen
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/store',  [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}',  [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/users/delete/{id}',  [UserController::class, 'destroy'])->name('users.delete');
    Route::get('/users-datatable', [UserController::class, 'getUsersDataTable']);
    //payment method managemen
    Route::get('/paymentMethod', [PaymentMethodController::class, 'index'])->name('paymentMethod');
    Route::post('/paymentMethod/store',  [PaymentMethodController::class, 'store'])->name('paymentMethod.store');
    Route::get('/paymentMethod/edit/{id}',  [PaymentMethodController::class, 'edit'])->name('paymentMethod.edit');
    Route::delete('/paymentMethod/delete/{id}',  [PaymentMethodController::class, 'destroy'])->name('paymentMethod.delete');
    Route::get('/paymentMethod-datatable', [PaymentMethodController::class, 'getPaymentMethodDataTable']);
    //wirehouse managemen
    Route::get('/wirehouses', [WirehouseController::class, 'index'])->name('wirehouses');
    Route::post('/wirehouses/store',  [WirehouseController::class, 'store'])->name('wirehouses.store');
    Route::get('/wirehouses/edit/{id}',  [WirehouseController::class, 'edit'])->name('wirehouses.edit');
    Route::delete('/wirehouses/delete/{id}',  [WirehouseController::class, 'destroy'])->name('wirehouses.delete');
    Route::get('/wirehouses-datatable', [WirehouseController::class, 'getWirehousesDataTable']);
    //shop managemen
    Route::get('/shops', [ShopController::class, 'index'])->name('shops');
    Route::post('/shops/store',  [ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/edit/{id}',  [ShopController::class, 'edit'])->name('shops.edit');
    Route::delete('/shops/delete/{id}',  [ShopController::class, 'destroy'])->name('shops.delete');
    Route::get('/shops-datatable', [ShopController::class, 'getShopsDataTable']);
});

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductStok;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'users' => User::where('role', '!=', 'admin')->where('role', '!=', 'owner')->count(),
            'customers' => Customer::count(),
            'product' => Product::count(),
        ];
        return view('admin.dashboard', $data);
    }
    public function getStokExpired()
    {
        $stok_masuk = ProductStok::where('expired_date', '<=', date('Y-m-d'))->where('type', 'Masuk')->sum('quantity');
        $stok_keluar = ProductStok::where('expired_date', '<=', date('Y-m-d'))->where('type', 'Keluar')->sum('quantity');
        $stok = $stok_masuk - $stok_keluar;
        $stok < 0 ? 0 : $stok;
        return $stok;
    }
    public function getStokRemainingExpired()
    {
        $stok_masuk = ProductStok::where('expired_date', '<=', date('Y-m-d', strtotime('+3 month')))->where('expired_date', '>', date('Y-m-d'))->where('type', 'Masuk')->sum('quantity');
        $stok_keluar = ProductStok::where('expired_date', '<=', date('Y-m-d', strtotime('+3 month')))->where('expired_date', '>', date('Y-m-d'))->where('type', 'Keluar')->sum('quantity');
        $stok = $stok_masuk - $stok_keluar;
        return $stok;
    }
    public function expiredAlert()
    {
        $data = [
            'expired' => $this->getStokExpired(),
            'remaining' => $this->getStokRemainingExpired()
        ];
        return response()->json($data);
    }
    public function getStokInput()
    {
        $stok = ProductStok::where('type', 'Masuk')->sum('quantity');
        return $stok;
    }
    public function getStokOut()
    {
        $stok = ProductStok::where('type', 'Keluar')->sum('quantity');
        return $stok;
    }

    public function getStokNotExpired()
    {
        $stok_masuk = ProductStok::where('expired_date', '>', date('Y-m-d'))->where('type', 'Masuk')->sum('quantity');
        $stok_keluar = ProductStok::where('expired_date', '>', date('Y-m-d'))->where('type', 'Keluar')->sum('quantity');
        $stok = $stok_masuk - $stok_keluar;
        return $stok;
    }
    public function getStokWirehouse()
    {
        $stok_masuk = ProductStok::where('type', 'Masuk')->sum('quantity');
        $stok_keluar = ProductStok::where('type', 'Keluar')->sum('quantity');
        $stok = $stok_masuk - $stok_keluar;
        return $stok;
    }
    public function getPriceStokInput()
    {
        $stok = ProductStok::where('type', 'Masuk')->sum('price_origin');
        return $stok;
    }
    public function getStokCard()
    {

        $data = [
            'stok_input' => $this->getStokInput(),
            'stok_out' => $this->getStokOut(),
            'stok_expired' => $this->getStokExpired(),
            'stok_not_expired' => $this->getStokNotExpired(),
            'stok_wirehouse' => $this->getStokWirehouse(),
            'price_stok_input' => $this->getPriceStokInput(),
        ];
        return response()->json($data);
    }
}

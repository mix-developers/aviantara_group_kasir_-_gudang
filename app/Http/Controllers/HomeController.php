<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductDamaged;
use App\Models\ProductStok;
use App\Models\Shop;
use App\Models\User;
use App\Models\Wirehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $product = Product::query();
        if (Auth::user()->role == 'Gudang') {
            $product->where('id_wirehouse', Auth::user()->id_wirehouse);
        }
        $product_data = $product->count();
        $data = [
            'title' => 'Dashboard',
            'users' => User::where('role', '!=', 'admin')->where('role', '!=', 'owner')->count(),
            'customers' => Customer::count(),
            'product' => $product_data,
            'shops' => Shop::count(),
            'wirehouses' => Wirehouse::count(),
        ];
        return view('admin.dashboard', $data);
    }
    public function getStokExpired()
    {
        $stok_masuk = ProductStok::where('expired_date', '<=', date('Y-m-d'))->where('type', 'Masuk')->where('sub_type', 'masuk');
        $stok_keluar = ProductStok::where('expired_date', '<=', date('Y-m-d'))->where('type', 'Keluar');
        //tambahkan produk rusak
        $rusak = ProductDamaged::where('expired_date', '<=', date('Y-m-d'));
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $stok_masuk->whereHas('product', function ($stok_masuk) use ($user) {
                $stok_masuk->where('id_wirehouse', $user->id_wirehouse);
            });
            $stok_keluar->whereHas('product', function ($stok_keluar) use ($user) {
                $stok_keluar->where('id_wirehouse', $user->id_wirehouse);
            });
            //rusak
            $rusak->whereHas('product', function ($rusak) use ($user) {
                $rusak->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $stok_keluar = $stok_keluar->sum('quantity');
        $stok_masuk = $stok_masuk->sum('quantity');
        //rusak
        $rusak = $rusak->sum('quantity_unit');
        $stok = $stok_masuk - $stok_keluar - $rusak;
        $stok < 0 ? 0 : $stok;
        return $stok;
    }
    public function getStokRemainingExpired()
    {
        $stok_masuk = ProductStok::where('expired_date', '<=', date('Y-m-d', strtotime('+3 month')))->where('expired_date', '>', date('Y-m-d'))->where('type', 'Masuk')->where('sub_type', 'masuk');
        $stok_keluar = ProductStok::where('expired_date', '<=', date('Y-m-d', strtotime('+3 month')))->where('expired_date', '>', date('Y-m-d'))->where('type', 'Keluar');
        //rusak
        $rusak = ProductDamaged::where('expired_date', '<=', date('Y-m-d', strtotime('+3 month')))->where('expired_date', '>', date('Y-m-d'));
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $stok_masuk->whereHas('product', function ($stok_masuk) use ($user) {
                $stok_masuk->where('id_wirehouse', $user->id_wirehouse);
            });
            $stok_keluar->whereHas('product', function ($stok_keluar) use ($user) {
                $stok_keluar->where('id_wirehouse', $user->id_wirehouse);
            });
            //rusak
            $rusak->whereHas('product', function ($rusak) use ($user) {
                $rusak->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $stok_keluar = $stok_keluar->sum('quantity');
        $stok_masuk = $stok_masuk->sum('quantity');
        //rusak
        $rusak = $rusak->sum('quantity_unit');
        $stok = $stok_masuk - $stok_keluar - $rusak;
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
        $stok = ProductStok::where('type', 'Masuk')->where('sub_type', 'masuk');
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $stok->whereHas('product', function ($stok) use ($user) {
                $stok->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $stok = $stok->sum('quantity');
        return $stok;
    }
    public function getStokOut()
    {
        $stok = ProductStok::where('type', 'Keluar');
        $stok_kembali = ProductStok::where('type', 'Masuk')->where('sub_type', 'kembali');
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $stok->whereHas('product', function ($stok) use ($user) {
                $stok->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $stok_kembali = $stok->sum('quantity');
        $stok = $stok->sum('quantity');
        return $stok - $stok_kembali;
    }

    public function getStokNotExpired()
    {
        $stok_masuk = ProductStok::where('expired_date', '>', date('Y-m-d'))->where('type', 'Masuk');
        $stok_keluar = ProductStok::where('expired_date', '>', date('Y-m-d'))->where('type', 'Keluar');
        //rusak
        $rusak = ProductDamaged::where('expired_date', '>', date('Y-m-d'));
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $stok_masuk->whereHas('product', function ($stok_masuk) use ($user) {
                $stok_masuk->where('id_wirehouse', $user->id_wirehouse);
            });
            $stok_keluar->whereHas('product', function ($stok_keluar) use ($user) {
                $stok_keluar->where('id_wirehouse', $user->id_wirehouse);
            });
            //rusak
            $rusak->whereHas('product', function ($rusak) use ($user) {
                $rusak->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $stok_keluar = $stok_keluar->sum('quantity');
        $stok_masuk = $stok_masuk->sum('quantity');
        //rusak
        $rusak = $rusak->sum('quantity_unit');
        $stok = $stok_masuk - $stok_keluar;
        return $stok;
    }
    public function getStokWirehouse()
    {
        $stok_masuk = ProductStok::where('type', 'Masuk');
        $stok_keluar = ProductStok::where('type', 'Keluar');
        //rusak
        $rusak = ProductDamaged::query();
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $stok_masuk->whereHas('product', function ($stok_masuk) use ($user) {
                $stok_masuk->where('id_wirehouse', $user->id_wirehouse);
            });
            $stok_keluar->whereHas('product', function ($stok_keluar) use ($user) {
                $stok_keluar->where('id_wirehouse', $user->id_wirehouse);
            });
            //rusak
            $rusak->whereHas('product', function ($rusak) use ($user) {
                $rusak->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $stok_keluar = $stok_keluar->sum('quantity');
        $stok_masuk = $stok_masuk->sum('quantity');
        //rusak
        $rusak = $rusak->sum('quantity_unit');
        $stok = $stok_masuk - $stok_keluar - $rusak;
        return $stok;
    }
    public function getPriceStokInput()
    {
        $stok = ProductStok::where('type', 'Masuk');
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $stok->whereHas('product', function ($stok) use ($user) {
                $stok->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $stok = $stok->sum('price_origin');
        return $stok;
    }
    public function getStokDamagedWirehouse()
    {
        $stok = ProductDamaged::sum('quantity_unit');
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
            'stok_damaged' => $this->getStokDamagedWirehouse(),
            'price_stok_input' => $this->getPriceStokInput(),
        ];
        return response()->json($data);
    }
    public function getSalesData()
    {
        // Dummy data with dates in 'dd/mm/yy' format
        $data = [
            [
                'tanggal' => '01/04/24', // Date in 'dd/mm/yy' format
                'penjualan' => 539.42,
                'stok_masuk' => 100,
                'Stok_keluar' => 100,
                'stok_rusak' => 10
            ],
            [
                'tanggal' => '05/04/24',
                'penjualan' => 540.67,
                'stok_masuk' => 90,
                'Stok_keluar' => 100,
                'stok_rusak' => 5
            ],
            [
                'tanggal' => '08/04/24',
                'penjualan' => 550.12,
                'stok_masuk' => 120,
                'Stok_keluar' => 100,
                'stok_rusak' => 12
            ]
        ];

        // Format the 'tanggal' field from 'dd/mm/yy' to a Unix timestamp
        foreach ($data as &$item) {
            $item['tanggal'] = Carbon::createFromFormat('d/m/y', $item['tanggal'])->timestamp * 1000; // Multiply by 1000 to match JS timestamp (milliseconds)
        }

        // Return the data as JSON
        return response()->json($data);
    }
}

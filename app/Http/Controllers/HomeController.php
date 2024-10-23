<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderWirehouse;
use App\Models\OrderWirehousePayment;
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
        $stok_kembali = $stok_kembali->sum('quantity');
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

    public function getChartPaid()
    {
        // Mengambil semua order
        $orders = OrderWirehouse::all();

        // Mendefinisikan variabel untuk menghitung jumlah Lunas dan Belum Lunas
        $lunasCount = 0;
        $belumLunasCount = 0;

        // Looping untuk menghitung jumlah order lunas dan belum lunas
        foreach ($orders as $OrderWirehouse) {
            $query = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id);
            if (Auth::user()->role == 'Gudang') {
                $user = Auth::user();
                $query->whereHas('order_wirehouse', function ($query) use ($user) {
                    $query->where('id_wirehouse', $user->id_wirehouse);
                });
            }
            $check_payment = $query->sum('paid');

            if ($check_payment >= $OrderWirehouse->total_fee) {
                // Jika pembayaran sudah lunas
                $lunasCount++;
            } else {
                // Jika pembayaran belum lunas
                $belumLunasCount++;
            }
        }

        // Membuat data yang akan dikirim sebagai JSON
        $data = [
            'labels' => ['Lunas', 'Belum Lunas'],
            'datasets' => [
                [
                    'label' => 'Jumlah Order',
                    'backgroundColor' => ['#4caf50', '#f44336'], // Warna untuk grafik
                    'data' => [$lunasCount, $belumLunasCount], // Data jumlah lunas dan belum lunas
                ]
            ]
        ];

        // Mengembalikan data sebagai JSON
        return response()->json($data);
    }
    public function getChartExpired()
    {

        $expiredCount = 0;
        $remainingCount = 0;
        $normalCount = 0;

        $allProducts = Product::all();

        foreach ($allProducts as $product) {
            $stok = ProductStok::where('id_product', $product->id);

            if (Auth::user()->role == 'Gudang') {
                $user = Auth::user();
                $stok->whereHas('product', function ($stok) use ($user) {
                    $stok->where('id_wirehouse', $user->id_wirehouse);
                });
            }

            if ($stok->count() != 0) {
                foreach ($stok->get() as $itemStok) {
                    if ($itemStok->type == 'Masuk') {
                        $stok_kembali = ProductStok::where('id_product', $product->id)
                            ->where('type', 'Masuk')
                            ->where('sub_type', 'kembali')
                            ->where('expired_date', $itemStok->expired_date)
                            ->sum('quantity');
                        $stok_keluar = ProductStok::where('id_product', $product->id)
                            ->where('type', 'Keluar')
                            ->where('expired_date', $itemStok->expired_date)
                            ->sum('quantity');
                        $stok_rusak = ProductDamaged::where('id_product', $product->id)
                            ->where('expired_date', $itemStok->expired_date)
                            ->sum('quantity_unit');

                        if ($stok_keluar >= 0) {
                            $total_stok = $itemStok->quantity - $stok_keluar - $stok_rusak + $stok_kembali;
                            if ($total_stok > 0) {
                                $currentDate = date('Y-m-d');
                                if ($itemStok->expired_date <= $currentDate) {
                                    // Expired stock
                                    $expiredCount += $total_stok;
                                } elseif ($itemStok->expired_date <= date('Y-m-d', strtotime('+3 months'))) {
                                    // Stock expiring within 3 months
                                    $remainingCount += $total_stok;
                                } else {
                                    // Normal stock
                                    $normalCount += $total_stok;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Prepare the JSON response for the chart
        $response = [
            'labels' => ['Expired', 'Remaining', 'Normal'],
            'datasets' => [[
                'label' => 'Stock Status',
                'backgroundColor' => ['#f44336', '#ff9800', '#4caf50'],
                'data' => [$expiredCount, $remainingCount, $normalCount]
            ]]
        ];

        // Return the JSON response
        return response()->json($response);
    }

    public function getChartOrderAllWirehouses()
    {

        $startDate = OrderWirehouse::min('created_at');
        $endDate = OrderWirehouse::max('created_at');

        $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();

        $warehousesData = [];

        $warehouseIdsQuery = OrderWirehouse::query();

        if (Auth::user()->role == 'Gudang') {
            $warehouseIdsQuery->where('id_wirehouse', Auth::user()->id_wirehouse);
        }

        $warehouseIds = $warehouseIdsQuery->distinct()->pluck('id_wirehouse');

        foreach ($warehouseIds as $warehouseId) {
            $warehouseName = Wirehouse::where('id', $warehouseId)->first()->name;
            $dataPoints = [];

            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                $totalOrders = OrderWirehouse::where('id_wirehouse', $warehouseId)
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->count();

                $dataPoints[] = [
                    'x' => strtotime($date->format('Y-m-d')) * 1000,
                    'y' => $totalOrders
                ];
            }

            // Add the dataset for this warehouse
            $warehousesData[] = [
                'label' =>  $warehouseName,
                'dataPoints' => $dataPoints
            ];
        }

        // Return the JSON response with all datasets
        return response()->json($warehousesData);
    }
}

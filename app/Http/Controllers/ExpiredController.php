<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ExpiredController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Pengelolaan Stok Kadaluarsa',
        ];
        return view('admin.expired.index', $data);
    }
    public function getExpiredDatatable(Request $request)
    {
        $data = Product::with(['product_stoks'])
            ->whereHas('product_stoks', function ($query) {
                $query->where('expired_date', '<', now());
            });
        if (Auth::user()->role == 'Gudang') {
            $data->where('id_wirehouse', Auth::user()->id_wirehouse);
        }

        return DataTables::of($data)
            ->addColumn('expired_date', function ($product) {
                $expiredDate = optional($product->product_stoks->sortBy('expired_date')->first())->expired_date;
                return '<span class="text-danger">' . ($expiredDate ? \Carbon\Carbon::parse($expiredDate)->format('Y-m-d') : '-') . '</span>';
            })
            ->addColumn('stok', function ($product) {
                // Menampilkan jumlah stok yang telah expired
                $expiredStockTotal = $product->product_stoks
                    ->where('expired_date', '<', now())
                    ->where('sub_type', '!=', 'kembali')
                    ->sum(function ($stok) {
                        if ($stok->type == 'Masuk') {
                            return $stok->quantity;
                        } elseif ($stok->type == 'Keluar') {
                            return -$stok->quantity;
                        }
                        return 0;
                    });

                return '<span class="text-danger">' . ($expiredStockTotal > 0 ? $expiredStockTotal . ' ' . $product->unit : '-') . '</span>';
            })
            ->addColumn('action', function ($product) {
                return '<button class="btn btn-sm btn-danger">Buang</button>';
            })
            ->rawColumns(['action', 'expired_date', 'stok'])
            ->make(true);
    }
    public function getExpiredChartData()
    {
        $currentDate = now();
        $startDate = $currentDate->copy()->subYear()->startOfMonth(); // 1 tahun lalu dari awal bulan
        $endDate = $currentDate->endOfMonth(); // Akhir bulan ini

        $nextMonthStart = $currentDate->copy()->addMonth()->startOfMonth(); // Bulan depan (April jika sekarang Maret)
        $nextYearEnd = $nextMonthStart->copy()->addYear()->endOfMonth(); // Setahun ke depan

        // Query untuk stok yang SUDAH kadaluarsa (termasuk bulan ini)
        $expiredDataQuery = ProductStok::selectRaw("
        DATE_FORMAT(expired_date, '%Y-%m') as month, 
        SUM(quantity) as total_expired, 
        SUM(price_origin) as total_loss
    ")
            ->whereBetween('expired_date', [$startDate, $endDate])
            ->where('sub_type', '!=', 'kembali');

        // Query untuk stok yang AKAN kadaluarsa setelah bulan ini
        $upcomingExpiredQuery = ProductStok::selectRaw("
        DATE_FORMAT(expired_date, '%Y-%m') as month, 
        SUM(quantity) as total_expired, 
        SUM(price_origin) as total_loss
    ")
            ->whereBetween('expired_date', [$nextMonthStart, $nextYearEnd])
            ->where('sub_type', '!=', 'kembali');

        if (Auth::user()->role == 'Gudang') {
            $expiredDataQuery->whereHas('product', function ($query) {
                $query->where('id_wirehouse', Auth::user()->id_wirehouse);
            });

            $upcomingExpiredQuery->whereHas('product', function ($query) {
                $query->where('id_wirehouse', Auth::user()->id_wirehouse);
            });
        }

        // Eksekusi query
        $expiredData = $expiredDataQuery->groupBy('month')->orderBy('month')->get();
        $upcomingExpiredData = $upcomingExpiredQuery->groupBy('month')->orderBy('month')->get();

        // Debugging: Cek apakah data sebelum Maret ada
        // dd($expiredData->toArray());

        // Format data untuk chart
        $chartData = [
            'labels' => [],
            'data' => [],
            'loss' => [],
            'total_loss' => 0,
            'upcoming_labels' => [],
            'upcoming_data' => [],
            'upcoming_loss' => []
        ];

        // Mengisi data untuk stok yang SUDAH kadaluarsa
        foreach ($expiredData as $data) {
            $chartData['labels'][] = \Carbon\Carbon::parse($data->month . '-01')->format('F Y');
            $chartData['data'][] = (int) $data->total_expired;
            $chartData['loss'][] = (float) $data->total_loss;
            $chartData['total_loss'] += (float) $data->total_loss;
        }

        // Mengisi data untuk stok yang AKAN kadaluarsa
        foreach ($upcomingExpiredData as $data) {
            $chartData['upcoming_labels'][] = \Carbon\Carbon::parse($data->month . '-01')->format('F Y');
            $chartData['upcoming_data'][] = (int) $data->total_expired;
            $chartData['upcoming_loss'][] = (float) $data->total_loss;
        }

        return response()->json($chartData);
    }
}
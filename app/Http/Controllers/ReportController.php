<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehouse;
use App\Models\PaymentMethod;
use App\Models\paymentMethodItem;
use App\Models\Product;
use App\Models\ProductDamaged;
use App\Models\ProductStok;
use App\Models\User;
use App\Models\Wirehouse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Generator\Method;

class ReportController extends Controller
{
    public function stok_wirehouse()
    {
        $data = [
            'title' => 'Laporan Stok Gudang',
        ];
        return view('admin.report.stok_wirehouse', $data);
    }
    public function pdf_stok_wirehouse(Request $request)
    {
        $query = ProductStok::orderByDesc('id')
            ->with(['product', 'user']);

        $user_name = 'Semua';
        $type_name = 'Semua';


        if ($request->has('user')) {
            $userId = $request->input('user');
            if ($userId !== '-') {
                $query->where('id_user', $userId);
                $table_user = User::find($userId);
                $user_name = $table_user->name;
            }
        }
        if ($request->has('type')) {
            $type = $request->input('type');
            if ($type !== '-') {
                if ($type == 'Stok Masuk') {
                    $query->where('type', 'Masuk');
                    $type_name = 'Stok Masuk';
                } else {
                    $query->where('type', 'Keluar');
                    $type_name = 'Stok Keluar';
                }
            }
        }
        if ($request->has('expired')) {
            $expired = $request->input('expired');
            if ($expired !== '-') {
                if ($expired == 'Telah Kadaluarsa') {
                    $query->where('expired_date', '<=', date('Y-m-d'));
                } elseif ($expired == 'Akan Kadaluarsa') {
                    $query->where('expired_date', '<=', date('Y-m-d', strtotime('+3 month')));
                } elseif ($expired == 'Belum Kadaluarsa') {
                    $query->where('expired_date', '>', date('Y-m-d'));
                    $query->where('expired_date', '>', date('Y-m-d', strtotime('+3 month')));
                }
            }
        }

        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');
            if ($fromDate != '' && $toDate != '') {
                if ($fromDate && $toDate) {
                    $fromDate = Carbon::parse($fromDate)->startOfDay()->toDateTimeString();
                    $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();

                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }
        if (Auth::user()->role == 'Gudang') {
            $user = Auth::user();
            $query->whereHas('product', function ($query) use ($user) {
                $query->where('id_wirehouse', $user->id_wirehouse);
            });
        }
        $data = $query->get();

        $pdf =  \PDF::loadView('admin.report.pdf.stok_wirehouse', [
            'data' => $data,
            'from_date' => $fromDate ?? '',
            'to_date' => $toDate ?? '',
            'user' => $user_name ?? '',
            'type' => $type_name ?? ''
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan Stok Gudang ' . date('Y-m-d H:i') . '.pdf');
    }
    public function income()
    {
        $data = [
            'title' => 'Laporan Pendapatan',
        ];
        return view('admin.report.income', $data);
    }
    public function reportDaily()
    {
        $data = [
            'title' => 'Laporan Harian Gudang',
        ];
        return view('admin.report.wirehouse_daily', $data);
    }
    public function pdf_income(Request $request)
    {
        $paymentMethodItem = paymentMethodItem::orderByDesc('id')
            ->with(['payment_method', 'user']);

        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');
            if ($fromDate != '' && $toDate != '') {
                if ($fromDate && $toDate) {
                    $fromDate = Carbon::parse($fromDate)->startOfDay()->toDateTimeString();
                    $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();

                    $paymentMethodItem->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }
        if ($request->has('method')  && $request->input('method') !== 'all') {
            $paymentMethodItem->where('id_payment_method', $request->input('method'));
            $method =  PaymentMethod::find($request->input('method'));
            if ($method) {
                $metode =  $method->method;
            } else {
                $metode = 'Semua';
            }
        } else {
            $metode = 'Semua';
        }
        if (Auth::user()->role == 'Gudang') {
            $paymentMethodItem->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
                ->where('paid', '>', 0);
        }
        $data = $paymentMethodItem->get();

        $pdf =  \PDF::loadView('admin.report.pdf.income', [
            'data' => $data,
            'from_date' => $fromDate ?? now(),
            'to_date' => $toDate ?? now(),
            'metode' => $metode
        ])->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan Pendapatan ' . date('Y-m-d H:i') . '.pdf');
    }
    public function pdf_daily(Request $request)
    {
        $paymentMethodItem = paymentMethodItem::orderByDesc('id')
            ->with(['payment_method', 'user']);

        $paymentMethodItem->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->where('paid', '>', 0);

        if (Auth::user()->role == 'Gudang') {
            $paymentMethodItem->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('order_wirehouses')
                    ->where('order_wirehouses.id_wirehouse', '=', Auth::user()->id_wirehouse);
            });
        }

        $data = $paymentMethodItem->get();

        $pdf =  \PDF::loadView('admin.report.pdf.daily', [
            'data' => $data,
        ])->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan Pendapatan ' . date('Y-m-d H:i') . '.pdf');
    }
    public function price()
    {
        $data = [
            'title' => 'Laporan Harga Produk',
        ];
        return view('admin.report.price', $data);
    }
    public function pdf_price(Request $request)
    {
        $query = Product::orderByDesc('id');
        if ($request->has('wirehouse')) {
            $wirehouseId = $request->input('wirehouse');
            if ($wirehouseId !== '-') {
                $query->where('id_wirehouse', $wirehouseId);
                $wirehouses = Wirehouse::find($wirehouseId);
                $wirehouse_name = $wirehouses->name;
            } else {
                $wirehouse_name = 'Semua';
            }
        } else {
            $wirehouse_name = 'Semua';
        }
        if (Auth::user()->role == 'Gudang') {
            $query->where('id_wirehouse', Auth::user()->id_wirehouse);
        }
        $data = $query->get();
        $pdf =  \PDF::loadView('admin.report.pdf.price', [
            'data' => $data,
            'wirehouse' => $wirehouse_name
        ])->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan Harga Produk ' . date('Y-m-d H:i') . '.pdf');
    }
    public function damaged()
    {
        $data = [
            'title' => 'Laporan Produk Rusak',
        ];
        return view('admin.report.damaged', $data);
    }
    public function pdf_damaged(Request $request)
    {
        $damaged = ProductDamaged::orderByDesc('id')->with(['product', 'user']);
        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');
            if ($fromDate != '' && $toDate != '') {
                if ($fromDate && $toDate) {
                    $fromDate = Carbon::parse($fromDate)->startOfDay()->toDateTimeString();
                    $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();

                    $damaged->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }
        $user_name = 'Semua';
        if ($request->has('user')) {
            $userId = $request->input('user');
            if ($userId !== '-') {
                $damaged->where('id_user', $userId);
                $table_user = User::find($userId);
                $user_name = $table_user->name;
            }
        }

        if ($request->has('type')) {
            $type = $request->input('type');
            if ($type !== '-') {
                $damaged->where('type', $type);
            }
        }
        $pdf =  \PDF::loadView('admin.report.pdf.damaged', [
            'data' => $damaged->get(),
            'from_date' => $fromDate ?? '',
            'to_date' => $toDate ?? '',
            'user' => $user_name,
            'type' => $type ?? 'Semua'
        ])->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan Produk Rusak ' . date('Y-m-d H:i') . '.pdf');
    }
    public function transactionWirehouses()
    {
        $data = [
            'title' => 'Laporan Penjualan Gudang',
        ];
        return view('admin.report.wirehouse', $data);
    }
    public function pdf_transactionWirehouses(Request $request)
    {
        $query = OrderWirehouse::orderByDesc('id')->with(['customer', 'product', 'wirehouse']);
        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');
            if ($fromDate != '' && $toDate != '') {
                // $paymentMethodItem->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate);
                if ($fromDate && $toDate) {
                    // Konversi tanggal ke format timestamp
                    $fromDate = Carbon::parse($fromDate)->startOfDay()->toDateTimeString();
                    $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();

                    // Lakukan pencarian berdasarkan rentang waktu
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }
        if (Auth::user()->role == 'Gudang') {
            $query->where('id_user', Auth::user()->id);
        }
        if ($request->has('delivery')) {
            $delivery = $request->input('delivery');
            if ($delivery !== '-') {
                $query->where('delivery', $delivery);
            }
        }
        if ($request->has('wirehouse')) {
            $wirehouse = $request->input('wirehouse');
            if ($wirehouse !== '-') {
                $query->where('id_wirehouse', $wirehouse);

                $wirehouses = Wirehouse::find($wirehouse);
                $wirehouse_name = $wirehouses->name;
            } else {
                $wirehouse_name = 'Semua';
            }
        } else {
            $wirehouse_name = 'Semua';
        }
        if (Auth::user()->role == 'Gudang') {
            $query->where('id_wirehouse', Auth::user()->id_wirehouse);
        }

        $OrderWirehouse = $query->get();

        $pdf =  \PDF::loadView('admin.report.pdf.wirehouse', [
            'data' => $OrderWirehouse,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'wirehouse' => $wirehouse_name
        ])->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan Transaksi Gudang ' . date('Y-m-d H:i') . '.pdf');
    }
    public function transactionShops()
    {
        $data = [
            'title' => 'Laporan Penjualan Toko',
        ];
        // return view('admin.report.shop', $data);
        return view('admin.soon', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehouse;
use App\Models\PaymentMethod;
use App\Models\paymentMethodItem;
use App\Models\Product;
use App\Models\Wirehouse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Mockery\Generator\Method;

class ReportController extends Controller
{
    public function income()
    {
        $data = [
            'title' => 'Laporan Pendapatan',
        ];
        return view('admin.report.income', $data);
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
        $data = $paymentMethodItem->get();

        $pdf =  \PDF::loadView('admin.report.pdf.income', [
            'data' => $data,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'metode' => $metode
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

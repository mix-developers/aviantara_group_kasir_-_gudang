<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function income()
    {
        $data = [
            'title' => 'Laporan Pendapatan',
        ];
        return view('admin.report.income', $data);
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
    public function transactionShops()
    {
        $data = [
            'title' => 'Laporan Penjualan Toko',
        ];
        return view('admin.report.shop', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProductDamaged;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductDamagedController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Pengelolaan Stok Rusak',
        ];
        return view('admin.damaged.index', $data);
    }
    public function getDamagedsDataTable()
    {
        $damaged = ProductDamaged::select(['id', 'id_product', 'id_user', 'photo', 'description', 'created_at', 'updated_at'])->orderByDesc('id')->with(['product', 'user']);

        return Datatables::of($damaged)

            ->addColumn('action', function ($damaged) {
                return view('admin.damaged.components.actions', compact('damaged'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

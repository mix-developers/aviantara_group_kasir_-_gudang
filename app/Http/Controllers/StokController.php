<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function stoks()
    {
        $data = [
            'title' => 'Pengelolaan Stok',
        ];
        return view('admin.stok.stok', $data);
    }
    public function products()
    {
        $data = [
            'title' => 'Data Produk',
        ];
        return view('admin.stok.produk', $data);
    }
    public function getProductsDataTable()
    {
        $products = Product::select(['id', 'name', 'unit', 'barcode', 'quantity_unit', 'photo', 'id_wirehouse', 'created_at', 'updated_at'])->orderByDesc('id');

        return Datatables::of($products)
            ->addColumn('produk', function ($product) {
                return '<strong>' . $product->name . '</strong><br><span class="text-muted"> Isi ' . $product->quantity_unit . ' Pcs /' . $product->unit . '</span>';
            })
            ->addColumn('action', function ($product) {
                return view('admin.stok.components.product.actions_product', compact('product'));
            })
            ->addColumn('wirehouse', function ($product) {
                return '<strong>' . $product->wirehouse->name . '</strong><br><span class="text-muted">' . $product->wirehouse->address . '</span>';
            })
            ->addColumn('stok', function ($product) {
                return '<span class="text-primary fw-bold">' . number_format(Product::getStok($product->id)) . '</span> ' . $product->unit;
            })
            ->rawColumns(['produk', 'action', 'wirehouse', 'stok'])
            ->make(true);
    }
}

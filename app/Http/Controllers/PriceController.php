<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PriceController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Harga Produk',
        ];
        return view('admin.price.index', $data);
    }
    public function show($id)
    {
        $product = Product::find($id);
        $data = [
            'title' => 'Riwayat Harga : ' . $product->name,
            'product' => $product
        ];
        return view('admin.price.show', $data);
    }
    public function getAll()
    {
        $price = ProductPrice::all();
        return response()->json($price);
    }
    public function getPricesDataTable(Request $request)
    {
        $query = Product::select(['id', 'barcode', 'name', 'id_wirehouse', 'quantity_unit', 'unit', 'sub_unit', 'created_at', 'updated_at'])->orderByDesc('id');
        if ($request->has('wirehouse')) {
            $wirehouseId = $request->input('wirehouse');
            if ($wirehouseId !== '-') {
                $query->where('id_wirehouse', $wirehouseId);
            }
        }
        $product = $query->get();
        return Datatables::of($product)
            ->addColumn('quantity', function ($product) {
                return $product->quantity_unit . ' ' . $product->sub_unit . ' /' . $product->unit;
            })
            ->addColumn('grosir', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $price = $price_grosir != null ? $price_grosir->price_grosir : 0;

                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $price_stok = $price_origin != null ? $price_origin->price_origin : 0;

                $color = $price <= $price_stok ? 'text-danger' : 'text-info';

                return '<span class="text-primary h3">Rp ' . number_format($price) . '</span> /' . $product->unit . '<br> <small class="' . $color . '">Modal : Rp ' . number_format($price_stok) . ' /' . $product->unit . '</small>';
            })
            ->addColumn('price_grosir', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $price = $price_grosir != null ? $price_grosir->price_grosir : 0;

                return $price;
            })
            ->addColumn('percentese_fee', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $grosir = $price_grosir != null ? $price_grosir->price_grosir : 0;

                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal = $price_origin != null ? $price_origin->price_origin : 0;

                $fee = $modal > 0 ? (($grosir - $modal) / $modal) * 100 : 0;
                $color = $fee <= 0 ? 'danger' : 'success';
                $fee_rupiah = $modal > 0 ? $grosir - $modal : 0;

                return '<span class="text-' . $color . ' h4"><i class="bx bx-trending-up"></i> ' . number_format($fee, 2, '.', '') . '% </span><br><small class="text-info text-center">Untung : Rp ' . number_format($fee_rupiah) . '</small>';
            })

            ->addColumn('wirehouse', function ($product) {
                return '<strong>' . $product->wirehouse->name . '</strong><br><span class="text-muted">' . $product->wirehouse->address . '</span>';
            })
            ->addColumn('action', function ($product) {
                return view('admin.price.components.actions', compact('product'));
            })
            ->rawColumns(['action', 'grosir', 'wirehouse', 'percentese_fee'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_product' => 'required|string|max:255',
            'price_grosir' => 'required|string|max:20',
        ]);

        $PriceData = [
            'id_product' => $request->input('id_product'),
            'id_user' => Auth::user()->id,
            'price_grosir' => $request->input('price_grosir'),
        ];

        ProductPrice::create($PriceData);
        $message = 'Berhasil menambah data';


        return response()->json(['message' => $message]);
    }

    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($product);
    }
    public function getPriceDetailDataTable($id)
    {
        $price = ProductPrice::select(['id', 'id_product', 'price_grosir', 'id_user', 'created_at', 'updated_at'])->orderByDesc('id')->where('id_product', $id)->with(['product', 'user'])->get();

        return Datatables::of($price)
            ->addColumn('date', function ($price) {
                return $price->created_at->format('d F Y');
            })
            ->addColumn('grosir', function ($price) {
                return 'Rp ' . number_format($price->price_grosir);
            })

            ->rawColumns(['date', 'grosir', 'retail'])
            ->make(true);
    }
}

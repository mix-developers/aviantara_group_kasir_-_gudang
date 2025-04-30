<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ShopProduct;
use App\Models\ShopProductPrice;
use App\Models\ShopProductStok;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopProductController extends Controller
{
    public function index()
    {
        $table = [
            'title' => 'Produk Toko/Kios',
        ];
        return view('admin.shop_product.index', $table);
    }
    public function getProductsDataTable(Request $request)
    {
        $query = ShopProduct::orderByDesc('id');
        if (Auth::user()->role == 'Kasir') {
            $query->where('id_shop', Auth::user()->id_shop);
        }
        $products = $query;

        return Datatables::of($products)

            ->addColumn('action', function ($product) {
                $edit = '<button class="btn btn-sm btn-warning" onclick="editProduct(' . $product->id . ')">Edit</button>';
                $delete = '<button class="btn btn-sm btn-danger" onclick="deleteProduct(' . $product->id . ')">Delete</button>';
                return '<div class="btn-group d-flex">' . $edit . ' ' . $delete . '</div>';
            })
            ->addColumn('isi', function ($product) {
                return $product->quantity_unit . ' ' . $product->sub_unit . ' /' . $product->unit;
            })
            ->addColumn('stock', function ($product) {
                return ShopProduct::getStok($product->id);
            })
            ->addColumn('stock_retail', function ($product) {
                return ShopProduct::getStok($product->id) * $product->quantity_unit . ' ' . $product->sub_unit;
            })
            ->rawColumns(['action', 'isi', 'stock', 'stock_retail'])
            ->make(true);
    }
    public function searchByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        $product = Product::where('barcode', $barcode)->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }
    }
    public function searchByBarcodeShop(Request $request)
    {
        $barcode = $request->input('barcode');

        $product = ShopProduct::where('barcode', $barcode)
            ->withSum(['stocks as qty_masuk' => function ($query) {
                $query->where('type', 'Masuk');
            }], 'qty')
            ->withSum(['stocks as qty_keluar' => function ($query) {
                $query->where('type', 'Keluar');
            }], 'qty')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        // Hitung stok
        $stok = ($product->qty_masuk ?? 0) - ($product->qty_keluar ?? 0);

        if ($stok <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok kosong'
            ]);
        }

        // Tambahkan stok ke response
        $product->stok = $stok;
        $product->price_retail = $product->latestPrice?->price_retail ?? 0;

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
    public function generateUniqueBarcode()
    {
        do {
            $barcode = str_pad(mt_rand(0, 999999999999999), 15, '0', STR_PAD_LEFT);

            $exists = DB::table('products')->where('barcode', $barcode)->exists() ||
                DB::table('product_shop')->where('barcode', $barcode)->exists();
        } while ($exists);

        return response()->json([
            'success' => true,
            'barcode' => $barcode
        ]);
    }
    public function store_product(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'quantity_unit' => 'required|string|max:20',
            'barcode' => 'string|max:20',
        ]);

        $ProductData = [
            'name' => $request->input('name'),
            'unit' => $request->input('unit'),
            'sub_unit' => $request->input('sub_unit'),
            'barcode' => $request->input('barcode'),
            'quantity_unit' => $request->input('quantity_unit'),
            'id_shop' => Auth::user()->id_shop,
        ];
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/photos', $fileName);
        }

        if ($request->filled('id')) {
            $Product = ShopProduct::find($request->input('id'));
            if (!$Product) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }
            $ProductData['photo'] = $filePath ?? $Product->photo;

            $Product->update($ProductData);
            $message = 'Berhasil mengedit data';
        } else {
            $ProductData['photo'] = $filePath ?? null;
            ShopProduct::create($ProductData);
            $message = 'Berhasil menambah data';
        }

        return response()->json(['message' => $message]);
    }
    public function getProducts(Request $request)
    {
        $products = ShopProduct::select(['id', 'barcode', 'name', 'sub_unit', 'quantity_unit'])
            ->withSum(['stocks as qty_masuk' => function ($query) {
                $query->where('type', 'Masuk');
            }], 'qty')
            ->withSum(['stocks as qty_keluar' => function ($query) {
                $query->where('type', 'Keluar');
            }], 'qty')
            ->with(['latestPrice' => function ($query) {
                $query->latest();
            }])
            ->get()
            ->map(function ($product) {
                $product->stok = (($product->qty_masuk ?? 0) * $product->quantity_unit) - ($product->qty_keluar ?? 0);
                $product->price_retail = $product->latestPrice?->price_retail ?? 0;
                return $product;
            });

        return DataTables::of($products)->make(true);
    }
    public function edit($id)
    {
        $Product = ShopProduct::find($id);


        if (!$Product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($Product);
    }
    public function destroy($id)
    {
        $Product = ShopProduct::find($id);
        ShopProductPrice::where('id_product', $id)->delete();
        ShopProductStok::where('id_product', $id)->delete();

        if (!$Product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $Product->delete();

        return response()->json(['message' => 'Berhasil menghapus data']);
    }
}
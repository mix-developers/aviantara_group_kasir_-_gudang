<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;

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
    public function pdf($id)
    {
        $product = Product::find($id);
        $data = ProductPrice::where('id_product', $id)->get();
        $pdf =  \PDF::loadView('admin.price.pdf', [
            'data' => $data,
            'product' => $product,
        ])->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan Riwayat harga ' . date('Y-m-d H:i') . '.pdf');
    }
    public function getAll()
    {
        $price = ProductPrice::all();
        return response()->json($price);
    }
    public function getNotPrice()
    {
        $productsWithoutPrice = Product::leftJoin('product_prices', 'products.id', '=', 'product_prices.id_product')
            ->whereNull('product_prices.id_product')
            ->select('products.id')
            ->count();

        return response()->json($productsWithoutPrice);
    }
    public function getPricesDataTable(Request $request)
    {
        $query = Product::orderByDesc('id');
        if ($request->has('wirehouse')) {
            $wirehouseId = $request->input('wirehouse');
            if ($wirehouseId !== '-') {
                $query->where('id_wirehouse', $wirehouseId);
            }
        }
        if (Auth::user()->role == 'Gudang') {
            $query->where('id_wirehouse', Auth::user()->id_wirehouse);
        }
        $product = $query;
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
                $fee = $fee < 0 ? 0 : $fee;
                $color = $fee <= 0 ? 'danger' : 'success';
                $fee_rupiah = $modal > 0 ? $grosir - $modal : 0;

                return '<span class="text-' . $color . ' h4"><i class="bx bx-trending-up"></i> ' . number_format($fee, 2, '.', '') . '% </span><br><small class="text-info text-center">Untung : Rp ' . number_format($fee_rupiah) . '</small>';
            })
            ->addColumn('percentese_fee_text', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $grosir = $price_grosir != null ? $price_grosir->price_grosir : 0;

                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal = $price_origin != null ? $price_origin->price_origin : 0;

                $fee = $modal > 0 ? (($grosir - $modal) / $modal) * 100 : 0;
                $color = $fee <= 0 ? 'danger' : 'success';
                $fee_rupiah = $modal > 0 ? $grosir - $modal : 0;

                return number_format($fee, 2, '.', '') . '% ';
            })

            ->addColumn('price_origin', function ($product) {
                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal = $price_origin != null ? $price_origin->price_origin : 0;
                return $modal;
            })
            ->addColumn('price_fee', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $grosir = $price_grosir != null ? $price_grosir->price_grosir : 0;

                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal = $price_origin != null ? $price_origin->price_origin : 0;

                return $modal > 0 ? $grosir - $modal : 0;
            })
            ->addColumn('wirehouse', function ($product) {
                return '<strong>' . $product->wirehouse->name . '</strong><br><span class="text-muted">' . $product->wirehouse->address . '</span>';
            })
            ->addColumn('stok', function ($product) {
                $stok = Product::getStok($product->id);

                return $stok;
            })
            ->addColumn('action', function ($product) {
                return view('admin.price.components.actions', compact('product'));
            })

            ->rawColumns(['action', 'grosir', 'wirehouse', 'percentese_fee', 'stok', 'price_origin', 'price_fee', 'percentese_fee_text'])
            ->make(true);
    }
    public function getPricesOrderDataTable(Request $request)
    {
        $query = Product::orderByDesc('id');

        $query->whereHas('product_prices', function ($q) {
            $q->where('price_grosir', '>', 0)
                ->orWhereNotNull('price_grosir');
        });

        if (Auth::user()->role == 'Gudang') {
            $query->where('id_wirehouse', Auth::user()->id_wirehouse);
        }
        $product = $query;
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
            ->addColumn('price_retail', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $price = $price_grosir != null ? $price_grosir->price_grosir / $product->quantity_unit : 0;

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
            ->addColumn('percentese_fee_text', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $grosir = $price_grosir != null ? $price_grosir->price_grosir : 0;

                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal = $price_origin != null ? $price_origin->price_origin : 0;

                $fee = $modal > 0 ? (($grosir - $modal) / $modal) * 100 : 0;
                $color = $fee <= 0 ? 'danger' : 'success';
                $fee_rupiah = $modal > 0 ? $grosir - $modal : 0;

                return number_format($fee, 2, '.', '') . '% ';
            })

            ->addColumn('price_origin', function ($product) {
                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal = $price_origin != null ? $price_origin->price_origin : 0;
                return $modal;
            })
            ->addColumn('price_fee', function ($product) {
                $price_grosir = ProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $grosir = $price_grosir != null ? $price_grosir->price_grosir : 0;

                $price_origin = ProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal = $price_origin != null ? $price_origin->price_origin : 0;

                return $modal > 0 ? $grosir - $modal : 0;
            })
            ->addColumn('wirehouse', function ($product) {
                return '<strong>' . $product->wirehouse->name . '</strong><br><span class="text-muted">' . $product->wirehouse->address . '</span>';
            })
            ->addColumn('stok', function ($product) {
                $stok = Product::getStok($product->id);

                return $stok;
            })
            ->addColumn('stok_retail', function ($product) {
                $stok = Product::getStok($product->id);

                return $stok * $product->quantity_unit;
            })
            ->addColumn('action', function ($product) {
                return view('admin.price.components.actions', compact('product'));
            })

            ->rawColumns(['action', 'grosir', 'wirehouse', 'percentese_fee', 'stok', 'stok_retail', 'price_origin', 'price_fee', 'percentese_fee_text', 'price_retail'])
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
        $price = ProductPrice::select(['id', 'id_product', 'price_grosir', 'id_user', 'created_at', 'updated_at'])->orderByDesc('id')->where('id_product', $id)->with(['product', 'user']);

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

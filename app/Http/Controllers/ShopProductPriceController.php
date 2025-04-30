<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Models\ShopProductPrice;
use App\Models\ShopProductStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ShopProductPriceController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Harga Produk ' . Auth::user()->shop->name,
        ];
        return view('admin.shop_price.index', $data);
    }
    public function show($id)
    {
        $product = ShopProduct::find($id);
        $data = [
            'title' => 'Riwayat Harga : ' . $product->name,
            'product' => $product
        ];
        return view('admin.shop_price.show', $data);
    }
    public function getPricesDataTable(Request $request)
    {
        $query = ShopProduct::orderByDesc('id');
        // if ($request->has('wirehouse')) {
        //     $wirehouseId = $request->input('wirehouse');
        //     if ($wirehouseId !== '-') {
        //         $query->where('id_wirehouse', $wirehouseId);
        //     }
        // }
        if (Auth::user()->role == 'Kasir') {
            $query->where('id_shop', Auth::user()->id_shop);
        }
        $product = $query;
        return DataTables::of($product)
            ->addColumn('quantity', function ($product) {
                return $product->quantity_unit . ' ' . $product->sub_unit . ' /' . $product->unit;
            })
            ->addColumn('grosir', function ($product) {
                // Ambil harga jual
                $price_selling = ShopProductPrice::where('id_product', $product->id)
                    ->orderByDesc('id')
                    ->first();
                $price_selling = is_object($price_selling) ? (int) $price_selling->price : 0;

                // Ambil harga modal (stok masuk terakhir)
                $price_cost = ShopProductStok::where('id_product', $product->id)
                    ->where('type', 'Masuk')
                    ->orderByDesc('id')
                    ->first();
                $price_cost = is_object($price_cost) ? (int) $price_cost->price : 0;

                // Tentukan warna berdasarkan perbandingan harga jual dan harga modal
                $color = $price_selling <= $price_cost ? 'text-danger' : 'text-info';

                return '<span class="text-primary h3">Rp ' . number_format($price_selling) . '</span> /' . $product->unit .
                    '<br> <small class="' . $color . '">Modal : Rp ' . number_format($price_cost) . ' /' . $product->unit . '</small>';
            })
            ->addColumn('retail', function ($product) {
                // Ambil harga jual
                $price_selling = ShopProductPrice::where('id_product', $product->id)
                    ->orderByDesc('id')
                    ->first();
                $price_selling = is_object($price_selling) ? (int) $price_selling->price_retail  : 0;

                // Ambil harga modal (stok masuk terakhir)
                $price_cost = ShopProductStok::where('id_product', $product->id)
                    ->where('type', 'Masuk')
                    ->orderByDesc('id')
                    ->first();
                $price_cost = is_object($price_cost) ? (int) $price_cost->price / $product->quantity_unit : 0;

                // Tentukan warna berdasarkan perbandingan harga jual dan harga modal
                $color = $price_selling <= $price_cost ? 'text-danger' : 'text-info';

                return '<span class="text-primary h3">Rp ' . number_format($price_selling) . '</span> /' . $product->sub_unit .
                    '<br> <small class="' . $color . '">Modal : Rp ' . number_format($price_cost) . ' /' . $product->sub_unit . '</small>';
            })

            ->addColumn('percentese_fee', function ($product) {
                $price = ShopProductPrice::where('id_product', $product->id)->orderByDesc('id')->first();
                $grosir =  is_object($price) ? (int) $price->price_retail : 0;

                $price = ShopProductStok::where('id_product', $product->id)->where('type', 'Masuk')->orderByDesc('id')->first();
                $modal =  is_object($price) ? (int) $price->price / $product->quantity_unit  : 0;

                $fee = $modal > 0 ? (($grosir - $modal) / $modal) * 100 : 0;
                $fee = $fee < 0 ? 0 : $fee;
                $color = $fee <= 0 ? 'danger' : 'success';
                $fee_rupiah = $modal > 0 ? $grosir - $modal : 0;

                return '<span class="text-' . $color . ' h4"><i class="bx bx-trending-up"></i> ' . number_format($fee, 2, '.', '') . '% </span><br><small class="text-info text-center">Untung : Rp ' . number_format($fee_rupiah) . '</small>';
            })


            ->addColumn('stok', function ($product) {
                $stok = ShopProduct::getStok($product->id);

                return $stok;
            })
            ->addColumn('action', function ($product) {
                return view('admin.price.components.actions', compact('product'));
            })

            ->rawColumns(['action', 'grosir', 'retail', 'wirehouse', 'percentese_fee', 'stok', 'price', 'price_fee', 'percentese_fee_text'])
            ->make(true);
    }
    public function getPriceDetailDataTable($id)
    {
        $price = ShopProductPrice::orderByDesc('id')->where('id_product', $id)->with(['product', 'user']);

        return Datatables::of($price)
            ->addColumn('date', function ($price) {
                return $price->created_at->format('d F Y');
            })
            ->addColumn('grosir', function ($price) {
                return 'Rp ' . number_format($price->price);
            })

            ->rawColumns(['date', 'grosir', 'retail'])
            ->make(true);
    }
    public function edit($id)
    {
        $product = ShopProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($product);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_product' => 'required|string|max:255',
            'price_grosir' => 'required|string|max:20',
        ]);
        $product = ShopProduct::find($request->input('id_product'));

        $PriceData = [
            'id_product' => $request->input('id_product'),
            'id_user' => Auth::user()->id,
            'id_shop' => Auth::user()->id_shop,
            'price' => $request->input('price_grosir') * $product->quantity_unit,
            'price_retail' => $request->input('price_grosir'),
        ];

        ShopProductPrice::create($PriceData);
        $message = 'Berhasil menambah data';


        return response()->json(['message' => $message]);
    }
    public function getNotPrice()
    {
        $productsWithoutPrice = ShopProduct::leftJoin('product_shop_prices', 'product_shop.id', '=', 'product_shop_prices.id_product')
            ->whereNull('product_shop_prices.id_product')
            ->select('product_shop.id')
            ->count();

        return response()->json($productsWithoutPrice);
    }
    public function getPriceHistory($productId)
    {
        $prices = ShopProductPrice::where('id_product', $productId)
            ->orderBy('created_at', 'asc')
            ->get(['created_at', 'price']);

        return response()->json($prices);
    }
}
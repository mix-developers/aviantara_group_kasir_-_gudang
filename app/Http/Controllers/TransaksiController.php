<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StokKios;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.shop_orders.index', ['title' => 'Transaksi Kios']);
    }
    public function cashier()
    {
        //
        return view('admin.shop_orders.cashier', ['title' => 'Transaksi Kios']);
    }

    public function getAll()
    {
        $transaksi = Transaksi::with('stok')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $transaksi]);
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = Product::where('barcode', $barcode)->first();
        // dd($product);

        $stok_kios = StokKios::with('shop', 'product', 'user')->where('id_product', $product->id)->first();

        if ($stok_kios) {
            return response()->json($stok_kios);
        } else {
            return response()->json([
                'error' => 'Product not found'
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 

        $transaksi = $this->generateTransactionCode();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
        ]);

        // $transaction = Transaction::create([
        //     'user_id' => $request->user_id,
        //     'total_price' => $request->total_price,
        // ]);

        foreach ($request->products as $product) {
            $stok_kios = StokKios::where('id', $product['product_id'])->first();
            if ($stok_kios->qty < $product['quantity']) {
                return response()->json(['success' => false, 'message' => 'Stok Tidak Cukup', 'nama_produk' => $stok_kios->product->name]);
            } else {
                Transaksi::create([
                    'user_id' => Auth::user()->id,
                    'stok_id' => $product['product_id'],
                    'nama_produk' => $stok_kios->product->name, //jangan lupa nama produk
                    'transaksi_id' => $transaksi,
                    'product_id' => $product['product_id'],
                    'qty' => $product['quantity'],
                    'harga' => $product['price'],
                    'total' => $request->total_price,
                ]);
                $stok_kios->qty = $stok_kios->qty - $product['quantity'];
                $stok_kios->update();
            }
        }

        return response()->json(['success' => true, 'transaksi_id' => $transaksi]);
    }

    private function generateTransactionCode()
    {
        return strtoupper(Str::random(8));
    }
}

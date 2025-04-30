<?php

namespace App\Http\Controllers;

use App\Models\Kios;
use App\Models\Product;
use App\Models\ShopProductStok;
use App\Models\ProductStok;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kios = Kios::find(Auth::user()->id_shop);
        return view('admin.shop_stock.index', ['title' => 'Input Stok Toko', 'kios' => $kios]);
    }

    public function getShop($id_shop)
    {
        $stok_kios = ShopProductStok::with('shop', 'product', 'user')->where('id_kios', $id_shop)->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $stok_kios]);
    }
    public function getAll()
    {
        $stok_kios = ShopProductStok::with('shop', 'product', 'user')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $stok_kios]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('barcode');
        $produk = ShopProduct::where('barcode', $query)->first();


        // Debugging
        // if ($produk->quantity_unit <= 0) {
        //     return response()->json(['error' => 'Product not found'], 404);
        // }
        return response()->json($produk);
    }

    public function store(Request $request)
    {
        //


        $request->validate([
            'id_product_add' => 'required|unique:product_shop_stoks,id_product',
            // 'type' => 'required|string|max:255',
            'qty' => 'required|numeric|max:20',
            'price' => 'required|numeric',
            'expired_date' => 'required'
        ]);


        $stokData = [
            'id_kios' => Auth::user()->id_shop, //ID KIOS NANTI ATUR SECARA OTOMATIS
            'id_user' => Auth::id(),
            'id_product' => $request->input('id_product_add'),
            'type' => $request->input('type') ?? 'Masuk',
            'qty' => $request->input('qty'),
            'price' => $request->input('price'),
            'expired_date' => $request->input('expired_date'),
            'description' => '-'
        ];

        if ($request->filled('id')) {
            $stok_kios = ShopProductStok::find($request->input('id'));
            if (!$stok_kios) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $stok_kios->update($stokData);
            $message = 'Berhasil mengedit data test';
        } else {
            ShopProductStok::create($stokData);
            $message = 'Berhasil menambah data';
        }

        return response()->json(['message' => $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopProductStok  $ShopProductStok
     * @return \Illuminate\Http\Response
     */
    public function show(ShopProductStok $ShopProductStok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopProductStok  $ShopProductStok
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $stok_kios = ShopProductStok::with('shop', 'product', 'user')->find($id);
        if (!$stok_kios) {
            # code...
            return response()->json(['message' => 'data tidak ditemukan'], 200);
        }

        return response()->json($stok_kios);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopProductStok  $ShopProductStok
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {


        $request->validate([
            'type' => 'required|string|max:255',
            'qty' => 'required|numeric|min:0|max:20',
            'price' => 'required|numeric',
            'expired_date' => 'required'
        ]);


        $stokData = [
            'id_kios' => 2, //ID KIOS NANTI ATUR SECARA OTOMATIS
            'id_user' => Auth::id(),
            'id_product' => $request->input('id_product'),
            'type' => $request->input('type'),
            'qty' => $request->input('qty'),
            'price' => $request->input('price'),
            'expired_date' => $request->input('expired_date'),
            'description' => '-'
        ];


        $stok_kios = ShopProductStok::find($request->input('id'));
        // dd($stok_kios);
        if (!$stok_kios) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $stok_kios->update($stokData);
        $message = 'Berhasil mengedit data test';

        return response()->json(['message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopProductStok  $ShopProductStok
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopProductStok $ShopProductStok)
    {
        //
    }
}
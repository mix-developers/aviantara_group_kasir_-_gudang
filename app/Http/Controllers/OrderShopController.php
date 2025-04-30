<?php

namespace App\Http\Controllers;

use App\Models\OrderShop;
use App\Models\OrderShopItem;
use App\Models\OrderShopPayment;
use App\Models\ShopProductStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\NilUlid;
use Yajra\DataTables\Facades\DataTables;

class OrderShopController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'order.id_user' => 'required|integer',
            'order.id_shop' => 'required|integer',
            'order.no_invoice' => 'required|string|max:191',
            'order.total_fee' => 'required|integer',
            'order.payment_received' => 'required|integer',
            'order.change' => 'required|integer',
            'order.fee' => 'nullable|integer',
            'order.additional_fee' => 'nullable|integer',
            'order.discount' => 'nullable|numeric',
            'order.discount_rupiah' => 'nullable|numeric',
            'order.description' => 'nullable|string',
            'items' => 'required|array',
            'payments' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Simpan order utama
            $order = OrderShop::create([
                'id_user' => $data['order']['id_user'],
                'id_shop' => $data['order']['id_shop'],
                'no_invoice' => $data['order']['no_invoice'],
                'total_fee' => $data['order']['total_fee'],
                'payment_received' => $data['order']['payment_received'],
                'change' => $data['order']['change'],
                'fee' => $data['order']['fee'] ?? 0,
                'additional_fee' => $data['order']['additional_fee'] ?? 0,
                'discount' => $data['order']['discount'] ?? 0,
                'discount_rupiah' => $data['order']['discount_rupiah'] ?? 0,
                'description' => $data['order']['description'] ?? '',
            ]);

            // Simpan semua item
            foreach ($data['items'] as $item) {
                OrderShopItem::create([
                    'id_order_shop' => $order->id,
                    'id_product' => $item['id_product'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'discount_rupiah' => $item['discount_rupiah'] ?? 0,
                    'subtotal' => $item['subtotal'],
                ]);
            }
            foreach ($data['items'] as $item) {
                ShopProductStok::create([
                    'id_kios' => Auth::user()->id_shop,
                    'id_user' => Auth::id(),
                    'id_product' => $item['id_product'],
                    'qty' => $item['quantity'],
                    'price' => $item['price'],
                    'expired_date' => now(),
                    'type' => 'Keluar',
                    'description' => 'Pembelian',
                ]);
            }

            // Simpan semua pembayaran
            foreach ($data['payments'] as $pay) {
                OrderShopPayment::create([
                    'id_order_shop' => $order->id,
                    'id_user' => $pay['id_user'],
                    'id_payment_method' => $pay['id_payment_method'],
                    'paid' => $pay['paid'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil disimpan',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan order: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getOrderShopDataTable()
    {
        $price = OrderShop::with(['user', 'shop', 'order_shop_payment.payment_method'])
            ->where('id_shop', Auth::user()->id_shop)
            ->orderBy('created_at', 'desc')
            ->get();

        return DataTables::of($price)
            ->addColumn('date', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->rawColumns(['date'])
            ->make(true);
    }
}
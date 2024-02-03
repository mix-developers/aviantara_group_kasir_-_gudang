<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehouse;
use App\Models\OrderWirehouseItem;
use App\Models\OrderWirehousePayment;
use App\Models\ProductStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderWirehouseController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Transaksi gudang',
        ];
        return view('admin.order_wirehouse.index', $data);
    }
    public function getInvoice($invoice)
    {
        $OrderWirehouse = OrderWirehouse::where('no_invoice', $invoice)->with(['customer', 'wirehouse', 'user'])->first();
        if ($OrderWirehouse != null) {
            return response()->json($OrderWirehouse);
        } else {
            return response()->json(['message' => 'Data tidak ditemukan']);
        }
    }
    public function getOrderWirehousesDataTable(Request $request)
    {
        $query = OrderWirehouse::select([
            'id',
            'id_customer',
            'id_user',
            'id_wirehouse',
            'total_fee',
            'additional_fee',
            'delivery',
            'address_delivery',
            'description',
            'created_at',
            'updated_at',
            'no_invoice',
            'send_bill'
        ])->orderByDesc('id')->with(['customer', 'product', 'wirehouse']);

        if ($request->has('delivery')) {
            $delivery = $request->input('delivery');
            if ($delivery !== '-') {
                $query->where('delivery', $delivery);
            }
        }
        if ($request->has('wirehouse')) {
            $wirehouse = $request->input('wirehouse');
            if ($wirehouse !== '-') {
                $query->where('id_wirehouse', $wirehouse);
            }
        }

        $OrderWirehouse = $query->get();
        return DataTables::of($OrderWirehouse)
            ->addColumn('action', function ($OrderWirehouse) {
                return view('admin.order_wirehouse.components.actions', compact('OrderWirehouse'));
            })
            ->addColumn('action_payment', function ($OrderWirehouse) {
                $check_payment = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');
                $data = [
                    'paid' => $check_payment,
                    'OrderWirehouse' => $OrderWirehouse
                ];
                return view('admin.payment.components.actions', $data);
            })
            ->addColumn('total_fee_text', function ($OrderWirehouse) {
                return 'Rp ' . number_format($OrderWirehouse->total_fee);
            })
            ->addColumn('tagihan', function ($OrderWirehouse) {
                $check_payment = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');
                if ($check_payment < $OrderWirehouse->total_fee) {
                    $total = $OrderWirehouse->total_fee - $check_payment;
                    $sisa = '<br><small class="text-danger">Sisa : Rp ' . number_format($total) . '</small>';
                } else {
                    $sisa = '';
                }
                return 'Rp ' . number_format($OrderWirehouse->total_fee) . $sisa;
            })
            ->addColumn('additional_fee_text', function ($OrderWirehouse) {
                return 'Rp ' . number_format($OrderWirehouse->additional_fee);
            })
            ->addColumn('delivery_text', function ($OrderWirehouse) {
                $badge =  ($OrderWirehouse->delivery == 1 ? 'bg-label-success' : 'bg-label-danger');
                return '<span class="badge ' . $badge . '">' . ($OrderWirehouse->delivery == 1 ? 'Ya' : 'Tidak') . '</span>';
            })
            ->addColumn('wirehouse', function ($OrderWirehouse) {
                return '<strong>' . $OrderWirehouse->wirehouse->name . '</strong><br><span class="text-muted">' . $OrderWirehouse->wirehouse->address . '</span>';
            })
            ->addColumn('payment', function ($OrderWirehouse) {
                $check_payment = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');
                if ($check_payment <= 0) {
                    $payment = 'Menunggu Pembayaran';
                    $color = 'text-danger';
                } elseif ($check_payment < $OrderWirehouse->total_fee) {
                    $payment = 'Proses Pencicilan';
                    $color = 'text-warning';
                } else {
                    $payment = 'Lunas';
                    $color = 'text-primary';
                }
                return '<strong class="' . $color . '">' . $payment . '</strong>';
            })

            ->rawColumns(['action', 'actions_payment', 'payment', 'total_fee_text', 'additional_fee_text', 'delivery_text', 'wirehouse', 'tagihan'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required|string|max:255',
            'id_wirehouse' => 'required|string|max:255',
            'total_fee' => 'required|max:12',
            'description' => 'string',
            'id_product.*' => ['required'],
            'subtotal.*' => ['required'],
            'expired_date.*' => ['required'],
            'quantity.*' => ['required'],
        ]);
        $milliseconds = round(microtime(true) * 1000);
        $order = new OrderWirehouse();

        $delivery = $request->input('delivery') == 'on' ? 1 : 0;
        $additional_fee = $request->input('additional_fee');
        $total_fee =  $request->input('total_fee');

        $order->id_customer = $request->input('id_customer');
        $order->id_wirehouse = $request->input('id_wirehouse');
        $order->id_user = Auth::user()->id;
        $order->total_fee = $additional_fee > 0 ? $additional_fee + $total_fee : $total_fee;
        $order->additional_fee = $request->input('additional_fee');
        $order->delivery = $delivery;
        $order->address_delivery = $request->input('address_delivery');
        $order->description = $request->input('description');
        $order->no_invoice =  'AVI-' . $milliseconds;

        if ($order->save()) {
            $id_products = $request->id_product;
            $subtotals = $request->subtotal;
            $expired_dates = $request->expired_date;
            $quantitys = $request->quantity;

            foreach ($id_products as $key => $id_product) {
                $orderItem = new OrderWirehouseItem();
                $orderItem->id_order_wirehouse = $order->id;
                $orderItem->id_product = $id_product;
                $orderItem->subtotal = $subtotals[$key];
                $orderItem->expired_date = $expired_dates[$key];
                $orderItem->quantity = $quantitys[$key];
                $orderItem->save();
            }
            foreach ($id_products as $key => $id_product) {
                $ProductStokOut = new ProductStok();
                $ProductStokOut->id_product = $id_product;
                $ProductStokOut->id_user = Auth::user()->id;
                $ProductStokOut->type = 'Keluar';
                $ProductStokOut->price_origin = 0;
                $ProductStokOut->description = 'Penjualan Gudang';
                $ProductStokOut->expired_date = $expired_dates[$key];
                $ProductStokOut->quantity = $quantitys[$key];
                $ProductStokOut->save();
            }

            $message = 'Berhasil menambah data';
        }


        return response()->json(['message' => $message]);
    }
    public function getOrderWIrehouseItems($id_order_wirehouse)
    {
        $items = OrderWirehouseItem::where('id_order_wirehouse', $id_order_wirehouse)->with(['product'])->get();
        $total = OrderWirehouseItem::where('id_order_wirehouse', $id_order_wirehouse)->sum('subtotal');
        $payment = OrderWirehousePayment::where('id_order_wirehouse', $id_order_wirehouse)->sum('paid');
        $data = [
            'items' => $items,
            'total' => $total,
            'payment' => $payment
        ];
        return response()->json($data);
    }
}

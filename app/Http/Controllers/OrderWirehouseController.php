<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehouse;
use App\Models\OrderWirehouseItem;
use App\Models\OrderWirehousePayment;
use App\Models\ProductStok;
use App\Models\Wirehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderWirehouseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'Gudang') {
            $wirehouse = Wirehouse::find($user->id_wirehouse);
            $add = ' : ' . $wirehouse->name;
        }
        $data = [
            'title' => 'Transaksi gudang ' . $add ?? '',
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
        $query = OrderWirehouse::orderByDesc('id')->with(['customer', 'product', 'wirehouse']);
        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');
            if ($fromDate != '' && $toDate != '') {
                // $paymentMethodItem->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate);
                if ($fromDate && $toDate) {
                    // Konversi tanggal ke format timestamp
                    $fromDate = Carbon::parse($fromDate)->startOfDay()->toDateTimeString();
                    $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();

                    // Lakukan pencarian berdasarkan rentang waktu
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }
        if (Auth::user()->role == 'Gudang') {
            $query->where('id_user', Auth::user()->id);
        }
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
        if (Auth::user()->role == 'Gudang') {
            $query->where('id_wirehouse', Auth::user()->id_wirehouse);
        }

        $OrderWirehouse = $query;
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
                    $terbayar = '<br><small class="text-primary">Terbayar : Rp ' . number_format($check_payment) . '</small>';
                    $sisa = '<br><small class="text-danger">Sisa : Rp ' . number_format($total) . '</small>';
                } else {
                    $terbayar = '';
                    $sisa = '';
                }
                return 'Rp ' . number_format($OrderWirehouse->total_fee) . $terbayar . $sisa;
            })
            ->addColumn('terbayar', function ($OrderWirehouse) {
                $check_payment = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');

                return $check_payment;
            })
            ->addColumn('sisa', function ($OrderWirehouse) {
                $check_payment = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');

                return $OrderWirehouse->total_fee - $check_payment;
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
            ->addColumn('date', function ($OrderWirehouse) {
                return $OrderWirehouse->created_at->format('d F Y');
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

            ->rawColumns(['date', 'action', 'actions_payment', 'payment', 'total_fee_text', 'additional_fee_text', 'delivery_text', 'wirehouse', 'tagihan', 'terbayar', 'sisa'])
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
        $discount =  $request->input('discount') > 0 ? $request->input('discount') / 100 : 1;

        $order->id_customer = $request->input('id_customer');
        $order->id_wirehouse = $request->input('id_wirehouse');
        $order->id_user = Auth::user()->id;
        $order->total_fee = $additional_fee > 0 ? ($additional_fee + $total_fee) * $discount  : $total_fee * $discount;
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
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|string|max:255',
            'id_customer' => 'required|string|max:255',
            'id_wirehouse' => 'required|string|max:255',
            'description' => 'string',
        ]);

        $order = OrderWirehouse::find($request->input('id'));
        $sub_total_item = OrderWirehouseItem::where('id_order_wirehouse', $order->id)->sum('subtotal');
        $additional_fee = $request->input('additional_fee');
        $discount = $request->input('discount');

        if ($discount > 0) {
            $nilai_discount = $discount / 100;
            if ($additional_fee != 0) {
                $total = ($sub_total_item + $additional_fee) * (1 - $nilai_discount);
            } else {
                $total =  $sub_total_item * (1 - $nilai_discount);
            }
        } else {
            $total = $sub_total_item + $additional_fee;
        }

        $delivery = $request->input('delivery') == 'on' ? 1 : 0;

        $order->total_fee = $total;
        $order->id_customer = $request->input('id_customer');
        $order->id_wirehouse = $request->input('id_wirehouse');
        $order->id_user = Auth::user()->id;
        $order->additional_fee = $additional_fee;
        $order->delivery = $delivery;
        $order->discount = $discount;
        $order->address_delivery = $request->input('address_delivery');
        $order->description = $request->input('description');

        if ($order->save()) {
            $message = 'Berhasil Memperbarui data';
        } else {
            $message = 'Gagal Memperbarui data';
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
    public function edit($id)
    {
        $data = OrderWirehouse::find($id);
        if (!$data) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }

        return response()->json($data);
    }
}

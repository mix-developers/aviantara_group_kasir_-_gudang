<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehouse;
use App\Models\OrderWirehouseItem;
use App\Models\OrderWirehousePayment;
use App\Models\paymentMethodItem;
use App\Models\Product;
use App\Models\ProductStok;
use App\Models\Wirehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\RunningLaravelDuskInProductionProvider;

class OrderWirehouseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'Gudang') {
            $wirehouse = Wirehouse::find($user->id_wirehouse);
            $add = ' : ' . $wirehouse->name;
        } else {
            $add = 'semua';
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
                $items = OrderWirehouseItem::where('id_order_wirehouse', $OrderWirehouse->id);

                $total_discount_rupiah = $items->sum('discount_rupiah');
                $total_discount_persen = $items->sum('discount_persen');

                $total_seharusnya = $items->sum('quantity') * $items->sum('price');

                $total_subtotal = $items->sum('subtotal');

                if ($OrderWirehouse->total_fee == $OrderWirehouse->fee && $total_discount_rupiah == 0 && $total_discount_persen == 0) {
                    return 'Rp ' . number_format($OrderWirehouse->fee);
                } elseif ($OrderWirehouse->total_fee != $OrderWirehouse->fee && $total_discount_rupiah > 0 || $total_discount_persen > 0) {
                    return ' <del class="text-danger">Rp ' . number_format($total_seharusnya) . '</del><br> Rp ' . number_format($OrderWirehouse->total_fee);
                } else {
                    return ' <del class="text-danger">Rp ' . number_format($OrderWirehouse->fee) . '</del><br> Rp ' . number_format($OrderWirehouse->total_fee);
                }
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
                return $OrderWirehouse->created_at->translatedFormat('d F Y');
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
    public function getOrderWirehouseItemDataTable($id)
    {
        $data = OrderWirehouseItem::where('id_order_wirehouse', $id)->with(['product']);
        return DataTables::of($data)
            ->addColumn('subtotal_text', function ($data) {
                if ($data->subtotal == ($data->price * $data->quantity)) {
                    return  number_format($data->subtotal);
                } else {
                    return ' <del class="text-danger">' . number_format($data->price * $data->quantity) . '</del><br>' . number_format($data->subtotal);
                }
            })
            ->rawColumns(['subtotal_text'])
            ->make(true);
    }

    public function store(Request $request)
    {
        // dd($request->discount_rupiah);
        // return response()->json($request->all());
        $request->validate([
            'id_customer' => 'required|string|max:255',
            'id_wirehouse' => 'required|string|max:255',
            'total_fee' => 'required|max:12',
            'description' => 'string',
            'id_product.*' => ['required'],
            'subtotal.*' => ['required'],
            'price.*' => ['required'],
            'expired_date.*' => ['required'],
            'quantity.*' => ['required'],
        ]);
        $milliseconds = round(microtime(true) * 1000);
        $order = new OrderWirehouse();

        $delivery = $request->input('delivery') == 'on' ? 1 : 0;
        $additional_fee = $request->input('additional_fee');
        $total_fee =  $request->input('total_fee');
        if ($request->input('discount') > 0) {
            $discount =  $request->input('discount') > 0 ? $request->input('discount') / 100 : 0;
        } else {
            $discount =  $request->input('discount_rupiah');
        }
        $order->id_customer = $request->input('id_customer');
        $order->id_wirehouse = $request->input('id_wirehouse');
        $order->id_user = Auth::user()->id;
        $order->discount =  $request->input('discount') ?? 0;
        $order->discount_rupiah =  $request->input('discount_rupiah') ?? 0;
        $order->fee =   $request->input('total_fee');
        if ($request->input('discount') > 0) {
            $order->total_fee = $additional_fee > 0
                ? ($total_fee + $additional_fee) * (1 - $discount)
                : $total_fee * (1 - $discount);
        } else {
            $order->total_fee =   $additional_fee > 0
                ? ($total_fee + $additional_fee) - $discount
                : $total_fee - $discount;
        }
        $order->additional_fee = $request->input('additional_fee');
        $order->delivery = $delivery;
        $order->address_delivery = $request->input('address_delivery');
        $order->description = $request->input('description');
        $order->due_date = $request->input('due_date') ?? null;
        $order->no_invoice =  'AVI-' . $milliseconds;

        if ($order->save()) {
            $id_products = $request->id_product;
            $subtotals = $request->subtotal;
            $prices = $request->price;
            $expired_dates = $request->expired_date;
            $quantitys = $request->quantity;
            $discount_rupiahs = $request->discount_rupiah;
            $discount_persens = $request->discount_persen;

            foreach ($id_products as $key => $id_product) {
                $orderItem = new OrderWirehouseItem();
                $orderItem->id_order_wirehouse = $order->id;
                $orderItem->id_product = $id_product;
                $orderItem->price = $prices[$key];
                $orderItem->discount_rupiah = $discount_rupiahs[$key] ?? 0;
                $orderItem->discount_persen = $discount_persens[$key] ?? 0;
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
        }


        return response()->json(['message' => 'Order berhasil dibuat.', 'order' => $order->id, 'tagihan' => $order->total_fee]);
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
                $fee = $sub_total_item;
            } else {
                $total =  $sub_total_item * (1 - $nilai_discount);
                $fee = $sub_total_item;
            }
        } else {
            $total = $sub_total_item + $additional_fee;
            $fee = $sub_total_item;
        }

        $delivery = $request->input('delivery') == 'on' ? 1 : 0;

        $order->total_fee = $total;
        $order->id_customer = $request->input('id_customer');
        $order->id_wirehouse = $request->input('id_wirehouse');
        $order->due_date = $request->input('due_date') ?? $order->due_date;
        $order->id_user = Auth::user()->id;
        $order->additional_fee = $additional_fee;
        $order->delivery = $delivery;
        $order->discount = $discount;
        $order->fee =  $fee;
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
    public function destroy($id)
    {
        $data = OrderWirehouse::find($id);
        $OrderWirehouseItem = OrderWirehouseItem::where('id_order_wirehouse', $id);

        foreach ($OrderWirehouseItem->get() as $item) {
            $ProductStokPrice = ProductStok::where('id_product', $item->id_product)->where('type', 'Masuk')->where('price_origin', '>', 0)->latest()->first();
            //masukkan kembali stok
            $ProductStokOut = new ProductStok();
            $ProductStokOut->id_product = $item->id_product;
            $ProductStokOut->id_user = Auth::user()->id;
            $ProductStokOut->type = 'Masuk';
            $ProductStokOut->sub_type = 'kembali';
            $ProductStokOut->price_origin = $ProductStokPrice->price_origin;
            $ProductStokOut->description = 'Pengembalian stok gudang';
            $ProductStokOut->expired_date = $item->expired_date;
            $ProductStokOut->quantity = $item->quantity;
            $ProductStokOut->save();
        }

        $OrderWirehouseItem->delete();

        OrderWirehousePayment::where('id_order_wirehouse', $id)->delete();
        paymentMethodItem::where('id_order_wirehouse', $id)->delete();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Berhasil menghapus data']);
    }
    public function printInvoice($id)
    {
        $data = OrderWirehouse::where('id', $id)->with(['customer', 'product'])->first();
        $items = OrderWirehouseItem::where('id_order_wirehouse', $id)->get();

        // $pdf =  \PDF::loadView('admin.order_wirehouse.pdf.print_invoice', [
        //     'data' => $data,
        //     'item' => $item
        // ])->setPaper('a4', 'potrait');

        // return $pdf->download('Invoice ' . $data->no_invoice . '.pdf');
        return view('admin.order_wirehouse.pdf.print_invoice', compact('data', 'items'));
    }
    public function invoice($invoice)
    {
        $order = OrderWirehouse::where('no_invoice', $invoice)->first();
        $data = [
            'title' => 'Detail : ' . $invoice,
            'order' => $order
        ];
        return view('admin.order_wirehouse.show', $data);
    }
    public function getOrderItemsDataTable($id)
    {
        $data = OrderWirehouseItem::with('product')->where('id_order_wirehouse', $id);

        return Datatables::of($data)
            ->addColumn('subtotal_text', function ($data) {
                if ($data->subtotal == ($data->price * $data->quantity)) {
                    return  number_format($data->subtotal);
                } else {
                    return ' <del class="text-danger">' . number_format($data->price * $data->quantity) . '</del><br>' . number_format($data->subtotal);
                }
            })
            ->rawColumns(['subtotal_text'])
            ->make(true);
    }
    public function store_discount(Request $request)
    {
        $id = $request->input('id');
        $items = OrderWirehouseItem::find($id);

        $discount_persen = $request->input('discount_persen') ?? 0;
        $discount_rupiah = $request->input('discount_rupiah') ?? 0;
        $original_price_item = $items->price * $items->quantity;

        if ($discount_persen > 0) {
            $new_subtotal = $original_price_item * (1 - ($discount_persen / 100));
        } elseif ($discount_rupiah > 0) {
            $new_subtotal = $original_price_item - $discount_rupiah;
        } else {
            $new_subtotal = $original_price_item;
        }

        $items->subtotal = $new_subtotal;
        $items->discount_persen = $discount_persen;
        $items->discount_rupiah = $discount_rupiah;
        $items->save();

        $order = OrderWirehouse::find($items->id_order_wirehouse);
        if ($order->discount > 0) {
            $order_discounted_fee = OrderWirehouseItem::where('id_order_wirehouse', $items->id_order_wirehouse)->sum('subtotal') * (1 - ($order->discount / 100));
        } elseif ($order->discount_rupiah > 0) {
            $order_discounted_fee =  OrderWirehouseItem::where('id_order_wirehouse', $items->id_order_wirehouse)->sum('subtotal') - $discount_rupiah;
        } else {
            $order_discounted_fee = OrderWirehouseItem::where('id_order_wirehouse', $items->id_order_wirehouse)->sum('subtotal');
        }
        // Update the order's total fee
        $order->total_fee = $order_discounted_fee;
        $order->fee = OrderWirehouseItem::where('id_order_wirehouse', $items->id_order_wirehouse)->sum('subtotal');
        $order->save();

        return response()->json([
            'message' => 'Discount applied successfully',
            'new_total' => $order_discounted_fee
        ]);
    }
}

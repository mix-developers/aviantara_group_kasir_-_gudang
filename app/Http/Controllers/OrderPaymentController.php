<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehouse;
use App\Models\OrderWirehouseItem;
use App\Models\OrderWirehousePayment;
use App\Models\OrderWirehouseRetailItem;
use App\Models\paymentMethodItem;
use App\Models\Wirehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderPaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $add = '';
        if ($user->role == 'Gudang') {
            $wirehouse = Wirehouse::find($user->id_wirehouse);
            $add = ' : ' . $wirehouse->name;
        }
        $data = [
            'title' => 'Pembayaran tagihan ' . $add ?? '',
        ];
        return view('admin.payment.index', $data);
    }
    public function send_bill($id)
    {
        $bill = OrderWirehouse::find($id);
        $bill->send_bill = 1;
        $bill->save();

        $bill_items = OrderWirehouseItem::where('id_order_wirehouse', $id)->get();

        // Nomor tujuan dan isi pesan
        $phone = $bill->customer->phone;
        $message =
            "Hai, " . $bill->customer->name . "\n==================\n" .
            "Tagihan untuk Order #" . $bill->no_invoice . "\n" .
            "Total tagihan sebesar: Rp " . number_format($bill->total_fee, 0, ',', '.') . "\n" .
            "Jatuh tempo : " . $bill->due_date . "\n" .
            "Biaya tambahan: Rp " . number_format($bill->additional_fee, 0, ',', '.') . "\n" .
            "\n===================\n Daftar produk yang dibeli:\n";

        foreach ($bill_items as $item) {
            $message .= "- " . $item->product->name . " (Jumlah: " . $item->quantity . " " . $item->product->unit . " ) " . "Rp " . number_format($item->subtotal) . "\n";
        }

        $message .= "\nTerima kasih telah berbelanja. \n #AVIANTARA GROUP";


        // Encode pesan agar bisa digunakan di URL
        $messageEncoded = rawurlencode($message);

        // URL WhatsApp API
        $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . $messageEncoded;

        return response()->json(['message' => 'Berhasil mengirim tagihan', 'whatsapp_url' => $whatsappUrl]);
    }

    public function getPaymentDetailDataTable($id_order_wirehouse)
    {
        $orderPayment = OrderWirehousePayment::select(['id', 'id_user', 'id_order_wirehouse', 'id_payment_method', 'paid', 'foto', 'created_at', 'updated_at'])
            ->with(['payment_method', 'user', 'order_wirehouse'])
            ->where('id_order_wirehouse', $id_order_wirehouse)
            ->get();

        return Datatables::of($orderPayment)
            ->addColumn('action', function ($orderPayment) {
                return view('admin.payment.components.actions_detail', compact('orderPayment'));
            })
            ->addColumn('trash', function ($orderPayment) {
                return view('admin.payment.components.actions_trash', compact('orderPayment'));
            })
            ->rawColumns(['action', 'trash'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_payment_method' => 'required|string|max:255',
            'id_order_wirehouse' => 'required|string|max:255',
        ]);

        $OrderWirehouse = OrderWirehouse::find($request->input('id_order_wirehouse'));

        $checkPaymentOld = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');

        $PaymentData = [
            'id_payment_method' => $request->input('id_payment_method'),
            'id_order_wirehouse' => $request->input('id_order_wirehouse'),
            'id_user' => Auth::user()->id,
        ];

        if ($request->filled('id')) {
            $OrderWirehousePayment = OrderWirehousePayment::find($request->input('id'));
            if (!$OrderWirehousePayment) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }
            if ($checkPaymentOld == 0) {
                $OrderWirehousePayment['paid'] = $request->input('paid') > $OrderWirehouse->total_fee ? $OrderWirehouse->total_fee : $request->input('paid');
            } else {
                $FinalPayment = $OrderWirehouse->total_fee - $checkPaymentOld;
                $OrderWirehousePayment['paid'] = $request->input('paid') > $FinalPayment ? $FinalPayment : $request->input('paid');
            }
            $OrderWirehousePayment->update($PaymentData);
            $message = 'Berhasil mengedit data';
        } else {
            $invoice = OrderWirehouse::where('id', $request->input('id_order_wirehouse'))->first()->no_invoice;

            $paymentItems = new paymentMethodItem;
            $paymentItems->id_user =  Auth::user()->id;
            $paymentItems->id_order_wirehouse =  $request->input('id_order_wirehouse');
            $paymentItems->id_payment_method = $request->input('id_payment_method');
            $paymentItems->paid = $request->input('paid') > $OrderWirehouse->total_fee ? $OrderWirehouse->total_fee : $request->input('paid');;
            $paymentItems->description = 'Pembayaran tagihan invoice ' . $invoice;

            $paymentItems->save();
            if ($checkPaymentOld == 0) {
                $PaymentData['paid'] = $request->input('paid') > $OrderWirehouse->total_fee ? $OrderWirehouse->total_fee : $request->input('paid');
            } else {
                $FinalPayment = $OrderWirehouse->total_fee - $checkPaymentOld;
                $PaymentData['paid'] = $request->input('paid') > $FinalPayment ? $FinalPayment : $request->input('paid');
            }
            OrderWirehousePayment::create($PaymentData);
            $message = 'Berhasil menambah data';
        }


        return response()->json(['message' => $message, 'order' => $request->input('id_order_wirehouse')]);
    }
    public function getDataTagihan(request $request)
    {
        $OrderWirehouse = OrderWirehouse::with(['customer', 'user'])->get();
    }
    public function printDelivery($id)
    {
        $data = OrderWirehouse::where('id', $id)->with(['customer', 'product'])->first();
        $items = OrderWirehouseItem::where('id_order_wirehouse', $id)->get();
        if ($items->count() <= 0) {
            $items = OrderWirehouseRetailItem::where('id_order_wirehouse', $id)->get();
        }
        // $pdf =  \PDF::loadView('admin.order_wirehouse.pdf.print_invoice', [
        //     'data' => $data,
        //     'item' => $item
        // ])->setPaper('a4', 'potrait');

        // return $pdf->download('Invoice ' . $data->no_invoice . '.pdf');
        return view('admin.payment.print.delivery', compact('data', 'items'));
    }
    public function destroy($id)
    {
        $payment = paymentMethodItem::find($id);
        $order = OrderWirehousePayment::find($id);

        if (!$payment || !$order) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $payment->delete();
        $order->delete();
    }
}

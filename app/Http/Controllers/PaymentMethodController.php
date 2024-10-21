<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehousePayment;
use App\Models\PaymentMethod;
use App\Models\paymentMethodItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Metode Pembayaran',
        ];
        return view('admin.payment_method.index', $data);
    }
    public function show($id)
    {
        $paymentMethod = PaymentMethod::find($id);
        $data = [
            'title' => 'Riwayat data pembayaran dengan metode : ' . $paymentMethod->method,
            'paymentMethod' => $paymentMethod
        ];
        return view('admin.payment_method.show', $data);
    }
    public function getAll()
    {
        $PaymentMethod = PaymentMethod::all();
        return response()->json($PaymentMethod);
    }

    public function getPaymentMethodDataTable()
    {
        $PaymentMethod = PaymentMethod::select(['id', 'method', 'enabled', 'created_at', 'updated_at'])->orderByDesc('id');

        return Datatables::of($PaymentMethod)
            ->addColumn('action', function ($PaymentMethod) {
                return view('admin.payment_method.components.actions', compact('PaymentMethod'));
            })
            ->addColumn('status', function ($PaymentMethod) {
                return view('admin.payment_method.components.enabled', compact('PaymentMethod'));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
    public function getPaymentMethodDetailDataTable($id)
    {
        $paymentMethodItem = paymentMethodItem::select(['id', 'id_user', 'id_payment_method', 'paid', 'description', 'created_at', 'updated_at'])
            ->orderByDesc('id')
            ->where('id_payment_method', $id)
            ->with(['payment_method', 'user']);

        return Datatables::of($paymentMethodItem)
            ->addColumn('date', function ($paymentMethodItem) {
                return $paymentMethodItem->created_at->format('d F Y');
            })
            ->rawColumns(['date'])
            ->make(true);
    }
    public function getReportPaymentsDataTable(Request $request)
    {
        $paymentMethodItem = paymentMethodItem::orderByDesc('id')
            ->with(['payment_method', 'user', 'order_wirehouses']);

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
                    $paymentMethodItem->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }
        if ($request->has('wirehouse')) {
            $wirehouseId = $request->input('wirehouse');
            if ($wirehouseId !== '-') {
                $paymentMethodItem->whereHas('order_wirehouse', function ($paymentMethodItem) use ($wirehouseId) {
                    $paymentMethodItem->where('id_wirehouse', $wirehouseId);
                });
            }
        }


        if ($request->has('method')  && $request->input('method') !== 'all') {
            $paymentMethodItem->where('id_payment_method', $request->input('method'));
        }
        return Datatables::of($paymentMethodItem)
            ->addColumn('date', function ($paymentMethodItem) {
                return $paymentMethodItem->created_at->format('d F Y');
            })
            ->addColumn('invoice', function ($paymentMethodItem) {
                $invoice = $paymentMethodItem->order_wirehouses->no_invoice;
                return  '<a href="' . url('/payments/invoice', $invoice) . '">' . $invoice . '</a>';
            })
            ->rawColumns(['date', 'invoice'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|string|max:255',
        ]);

        $paymentMethodData = [
            'method' => $request->input('method'),
            'enabled' => $request->input('enabled') ?? 1,
        ];

        if ($request->filled('id')) {
            $PaymentMethod = PaymentMethod::find($request->input('id'));
            if (!$PaymentMethod) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $PaymentMethod->update($paymentMethodData);
            $message = 'Berhasil Mengedit data';
        } else {
            PaymentMethod::create($paymentMethodData);
            $message = 'Berhasil menambah data';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $PaymentMethod = PaymentMethod::find($id);

        if (!$PaymentMethod) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $PaymentMethod->delete();

        return response()->json(['message' => 'Berhasil menghapus data']);
    }
    public function edit($id)
    {
        $PaymentMethod = PaymentMethod::find($id);

        if (!$PaymentMethod) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($PaymentMethod);
    }
    public function getTotalPaymentMethod(Request $request, $id)
    {
        $data = paymentMethodItem::where('id_payment_method', $id);

        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');

            if ($fromDate != '' && $toDate != '') {
                // Convert dates to timestamps
                $fromDate = Carbon::parse($fromDate)->startOfDay()->toDateTimeString();
                $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();

                // Apply date filter
                $data->whereBetween('created_at', [$fromDate, $toDate]);
            }
        }
        if ($request->has('wirehouse')) {
            $wirehouseId = $request->input('wirehouse');
            if ($wirehouseId !== '-') {
                $data->whereHas('order_wirehouse', function ($data) use ($wirehouseId) {
                    $data->where('id_wirehouse', $wirehouseId);
                });
            }
        }
        $totalPaid = $data->sum('paid');
        return response()->json(['total' => $totalPaid]);
    }
}

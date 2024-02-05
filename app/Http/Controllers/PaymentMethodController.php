<?php

namespace App\Http\Controllers;

use App\Models\OrderWirehousePayment;
use App\Models\PaymentMethod;
use App\Models\paymentMethodItem;
use Illuminate\Http\Request;
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
            'title' => 'Data pembayaran dengan metode : ' . $paymentMethod->method,
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
        $PaymentMethod = PaymentMethod::select(['id', 'method', 'enabled', 'created_at', 'updated_at'])->orderByDesc('id')->get();

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
    public function getTotalPaymentMethod($id)
    {
        $data = paymentMethodItem::where('id_payment_method', $id)->sum('paid');
        return response()->json(['total' => $data]);
    }
}

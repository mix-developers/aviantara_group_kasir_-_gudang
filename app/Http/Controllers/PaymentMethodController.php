<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
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
                return response()->json(['message' => 'Payment method not found'], 404);
            }

            $PaymentMethod->update($paymentMethodData);
            $message = 'Payment method updated successfully';
        } else {
            PaymentMethod::create($paymentMethodData);
            $message = 'Payment method created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $PaymentMethod = PaymentMethod::find($id);

        if (!$PaymentMethod) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        $PaymentMethod->delete();

        return response()->json(['message' => 'Payment method deleted successfully']);
    }
    public function edit($id)
    {
        $PaymentMethod = PaymentMethod::find($id);

        if (!$PaymentMethod) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        return response()->json($PaymentMethod);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProductDamaged;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductDamagedController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Pengelolaan Stok Rusak',
        ];
        return view('admin.damaged.index', $data);
    }
    public function getDamagedsDataTable(Request $request)
    {
        $damaged = ProductDamaged::orderByDesc('id')->with(['product', 'user']);
        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');
            if ($fromDate != '' && $toDate != '') {
                if ($fromDate && $toDate) {
                    $fromDate = Carbon::parse($fromDate)->startOfDay()->toDateTimeString();
                    $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();

                    $damaged->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }
        if ($request->has('user')) {
            $userId = $request->input('user');
            if ($userId !== '-') {
                $damaged->where('id_user', $userId);
            }
        }
        if ($request->has('type')) {
            $type = $request->input('type');
            if ($type !== '-') {
                $damaged->where('type', $type);
            }
        }
        return Datatables::of($damaged)

            ->addColumn('action', function ($damaged) {
                return view('admin.damaged.components.actions', compact('damaged'));
            })
            ->addColumn('tanggal', function ($damaged) {
                return $damaged->created_at->format('d/m/Y');
            })
            ->rawColumns(['action', 'tanggal'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_product' => 'required|string|max:255',
            'photo' => 'required|file|mimes:jpeg,png,jpg,gif',
            'photo2' => 'file|mimes:jpeg,png,jpg,gif',
            'type' => 'required|string',
            'quantity_unit' => 'required|string',
            'expired_date' => 'required|string',
            'description' => 'required|string',
        ]);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/photos', $fileName);
        } else {
            return response()->json(['message' => 'File photo tidak ditemukan'], 400);
        }
        if ($request->hasFile('photo2')) {
            $file = $request->file('photo2');
            $fileName2 = time() . '_' . $file->getClientOriginalName();
            $filePath2 = $file->storeAs('public/photos', $fileName2);
        }
        $productData = [
            'id_product' => $request->input('id_product'),
            'photo' => $filePath,
            'photo2' => $filePath2,
            'type' => $request->input('type'),
            'quantity_unit' => $request->input('quantity_unit'),
            'quantity_sub_unit' => $request->input('quantity_sub_unit'),
            'expired_date' => $request->input('expired_date'),
            'description' => $request->input('description'),
            'id_user' => Auth::id(),
        ];

        if ($request->filled('id')) {
            $productDamaged = ProductDamaged::find($request->input('id'));
            if (!$productDamaged) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $productDamaged->update($productData);
            $message = 'Berhasil mengedit data';
        } else {
            // Create new record
            ProductDamaged::create($productData);
            $message = 'Berhasil menambah data';
        }

        return response()->json(['message' => $message]);
    }

    public function destroy($id)
    {
        $ProductDamageds = ProductDamaged::find($id);

        if (!$ProductDamageds) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $ProductDamageds->delete();

        return response()->json(['message' => 'Berhasil menghapus data']);
    }
    public function edit($id)
    {
        $ProductDamaged = ProductDamaged::find($id);

        if (!$ProductDamaged) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }

        return response()->json($ProductDamaged);
    }
}

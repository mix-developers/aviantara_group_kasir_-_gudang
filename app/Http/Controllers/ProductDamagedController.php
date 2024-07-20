<?php

namespace App\Http\Controllers;

use App\Models\ProductDamaged;
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
    public function getDamagedsDataTable()
    {
        $damaged = ProductDamaged::orderByDesc('id')->with(['product', 'user']);

        return Datatables::of($damaged)

            ->addColumn('action', function ($damaged) {
                return view('admin.damaged.components.actions', compact('damaged'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_product' => 'required|string|max:255',
            'photo' => 'required|file|mimes:jpeg,png,jpg,gif',
            'type' => 'required|string',
            'total' => 'required|string',
            'satuan' => 'required|string',
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
        $productData = [
            'id_product' => $request->input('id_product'),
            'photo' => $filePath,
            'type' => $request->input('type'),
            'total' => $request->input('total'),
            'satuan' => $request->input('satuan'),
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

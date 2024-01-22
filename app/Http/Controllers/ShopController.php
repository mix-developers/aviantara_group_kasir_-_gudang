<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use function Psy\sh;

class ShopController extends Controller
{
    public function index()
    {
        $shop = Shop::all();
        $data = [
            'title' => 'Toko/Kios',
            'shops' => $shop
        ];
        return view('admin.shop.index', $data);
    }
    public function getShopsDataTable()
    {
        $shop = Shop::select(['id', 'name', 'address', 'created_at', 'updated_at'])->orderByDesc('id');

        return Datatables::of($shop)
            ->addColumn('shop', function ($shop) {
                return view('admin.shop.components.shop', compact('shop'));
            })
            ->addColumn('action', function ($shop) {
                return view('admin.shop.components.actions', compact('shop'));
            })
            ->rawColumns(['action', 'shop'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:20',
        ]);

        $ShopData = [
            'name' => $request->input('name'),
            'address' => $request->input('address'),
        ];

        if ($request->filled('id')) {
            $Shop = Shop::find($request->input('id'));
            if (!$Shop) {
                return response()->json(['message' => 'Shop not found'], 404);
            }

            $Shop->update($ShopData);
            $message = 'Shop updated successfully';
        } else {
            Shop::create($ShopData);
            $message = 'Shop created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $Shop = Shop::find($id);

        if (!$Shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $Shop->delete();

        return response()->json(['message' => 'Shop deleted successfully']);
    }
    public function edit($id)
    {
        $Shop = Shop::find($id);

        if (!$Shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        return response()->json($Shop);
    }
}

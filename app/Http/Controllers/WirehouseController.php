<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wirehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WirehouseController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Gudang',
            'wirehouses' => Wirehouse::all()
        ];
        return view('admin.wirehouse.index', $data);
    }
    public function getWirehouseTotalProduct($id)
    {
        $data = Wirehouse::getProduct($id);
        return response()->json(['total' => $data]);
    }
    public function show($id)
    {
        $wirehouse = Wirehouse::find($id);
        $data = [
            'title' => 'Data ' . $wirehouse->name,
            'wirehouse' => $wirehouse
        ];
        return view('admin.wirehouse.show', $data);
    }
    public function getAll()
    {
        $wirehouse = Wirehouse::all();
        return response()->json($wirehouse);
    }
    public function getWirehouseDetailDataTable($id)
    {
        $products = Product::select(['id', 'name', 'unit', 'barcode', 'quantity_unit', 'photo', 'id_wirehouse', 'created_at', 'updated_at'])->orderByDesc('id')->where('id_wirehouse', $id)->with(['wirehouse']);

        return Datatables::of($products)
            ->addColumn('produk', function ($product) {
                return '<strong>' . $product->name . '</strong><br><span class="text-muted"> Isi ' . $product->quantity_unit . ' Pcs /' . $product->unit . '</span>';
            })
            ->addColumn('stok', function ($product) {
                return view('admin.stok.components.product.stok', compact('product'));
            })
            ->addColumn('wirehouse', function ($product) {
                return '<strong>' . $product->wirehouse->name . '</strong><br><span class="text-muted">' . $product->wirehouse->address . '</span>';
            })
            ->rawColumns(['produk', 'wirehouse', 'stok'])
            ->make(true);
    }
    public function getWirehousesDataTable()
    {
        $wirehouse = Wirehouse::select(['id', 'name', 'address', 'created_at', 'updated_at'])->orderByDesc('id');

        return Datatables::of($wirehouse)

            ->addColumn('wirehouse', function ($wirehouse) {
                return view('admin.wirehouse.components.wirehouse', compact('wirehouse'));
            })
            ->addColumn('action', function ($wirehouse) {
                return view('admin.wirehouse.components.actions', compact('wirehouse'));
            })
            ->rawColumns(['action', 'wirehouse'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:20',
        ]);

        $wirehouseData = [
            'name' => $request->input('name'),
            'address' => $request->input('address'),
        ];

        if ($request->filled('id')) {
            $wirehouse = Wirehouse::find($request->input('id'));
            if (!$wirehouse) {
                return response()->json(['message' => 'wirehouse not found'], 404);
            }

            $wirehouse->update($wirehouseData);
            $message = 'wirehouse updated successfully';
        } else {
            Wirehouse::create($wirehouseData);
            $message = 'customer created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $wirehouse = Wirehouse::find($id);

        if (!$wirehouse) {
            return response()->json(['message' => 'Wirehouse not found'], 404);
        }

        $wirehouse->delete();

        return response()->json(['message' => 'Wirehouse deleted successfully']);
    }
    public function edit($id)
    {
        $wirehouse = Wirehouse::find($id);

        if (!$wirehouse) {
            return response()->json(['message' => 'wireh$wirehouse not found'], 404);
        }

        return response()->json($wirehouse);
    }
}

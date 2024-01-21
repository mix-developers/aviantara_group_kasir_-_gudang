<?php

namespace App\Http\Controllers;

use App\Models\Wirehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WirehouseController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Gudang',
        ];
        return view('admin.wirehouse.index', $data);
    }
    public function getWirehousesDataTable()
    {
        $wirehouse = Wirehouse::select(['id', 'name', 'address', 'created_at', 'updated_at'])->orderByDesc('id');

        return Datatables::of($wirehouse)
            ->addColumn('action', function ($wirehouse) {
                return view('admin.wirehouse.components.actions', compact('wirehouse'));
            })
            ->rawColumns(['action'])
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

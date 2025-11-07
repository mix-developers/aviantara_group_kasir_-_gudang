<?php

namespace App\Http\Controllers;

use App\Models\Opname;
use App\Models\OpnameSchedule;
use App\Models\Product;
use App\Models\User;
use App\Models\Wirehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

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
        $staff = User::where('role', 'Gudang')->where('id_wirehouse', $wirehouse->id)->get();
        $data = [
            'title' => 'Data ' . $wirehouse->name,
            'wirehouse' => $wirehouse,
            'staff' => $staff,
        ];
        return view('admin.wirehouse.show', $data);
    }
    public function getAll()
    {
        if (Auth::user()->role != 'Gudang') {
            $wirehouse = Wirehouse::all();
        } elseif (Auth::user()->role == 'Gudang') {
            $wirehouse = Wirehouse::where('id', Auth::user()->id_wirehouse)->get();
        }
        return response()->json($wirehouse);
    }
    public function getWirehouseDetailDataTable($id)
    {
        $products = Product::orderByDesc('id')->where('id_wirehouse', $id)->with(['wirehouse'])->get();

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
            ->addColumn('logo', function ($product) {
                return '<img src="' . ($product->logo ? asset('storage/' . $product->logo) : asset('img/logo.png')) . '" alt="Logo" width="50">';
            })
            ->rawColumns(['produk', 'wirehouse', 'stok', 'logo'])
            ->make(true);
    }
    public function getWirehousesDataTable()
    {
        $wirehouse = Wirehouse::select(['id', 'name', 'address', 'ud_cv', 'logo', 'created_at', 'updated_at'])->orderByDesc('id')->get();

        return Datatables::of($wirehouse)

            ->addColumn('wirehouse', function ($wirehouse) {
                return view('admin.wirehouse.components.wirehouse', compact('wirehouse'));
            })
            ->addColumn('action', function ($wirehouse) {
                return view('admin.wirehouse.components.actions', compact('wirehouse'));
            })
            ->addColumn('action_opname', function ($wirehouse) {
                $shceduleBtn = '<button class="btn btn-sm btn-warning" onclick="schedule(' . $wirehouse->id . ')">Jadwalkan</button>';
                $viewBtn = '<a class="btn btn-sm btn-primary" href="opname-wirehouse/' . $wirehouse->id . '">Lihat</a>';
                return Auth::user()->role == 'Admin' ? $shceduleBtn . ' ' . $viewBtn : $viewBtn;
            })
            ->addColumn('last_opname', function ($wirehouse) {
                return Opname::where('id_wirehouse', $wirehouse->id)
                    ->latest()
                    ->first()
                    ?->created_at?->format('d F Y') ?? '<span class="text-danger">Belum Pernah</span>';
            })
            ->addColumn('schedule', function ($wirehouse) {
                $latestSchedule = OpnameSchedule::where('id_wirehouse', $wirehouse->id)
                    ->latest()
                    ->first();

                return $latestSchedule && $latestSchedule->date_schedule
                    ? 'Tanggal : ' . $latestSchedule->date_schedule
                    : '<span class="text-danger">Belum Dijadwalkan</span>';
            })
            ->addColumn('logo', function ($wirehouse) {
                return '<img src="' . ($wirehouse->logo ? asset('storage/' . $wirehouse->logo) : asset('img/logo.png')) . '" alt="Logo" width="50">';
            })
            ->rawColumns(['action', 'wirehouse', 'action_opname', 'last_opname', 'schedule', 'logo'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:20',
            'ud_cv' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $wirehouseData = [
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'ud_cv' => $request->input('ud_cv'),
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $wirehouseData['logo'] = $logoPath;
        }

        if ($request->filled('id')) {
            $wirehouse = Wirehouse::find($request->input('id'));
            if (!$wirehouse) {
                return response()->json(['message' => 'wirehouse not found'], 404);
            }
            if ($request->hasFile('logo') && $wirehouse->logo && Storage::disk('public')->exists($wirehouse->logo)) {
                Storage::disk('public')->delete($wirehouse->logo);
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
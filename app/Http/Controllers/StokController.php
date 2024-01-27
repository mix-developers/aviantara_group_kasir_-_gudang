<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function stoks()
    {
        $data = [
            'title' => 'Pengelolaan Stok',
        ];
        return view('admin.stok.stok', $data);
    }
    public function products()
    {
        $data = [
            'title' => 'Data Produk',
        ];
        return view('admin.stok.produk', $data);
    }
    public function show_product($id)
    {
        $product = Product::find($id);
        $data = [
            'title' => 'Rincian stok pada : ' . $product->name,
            'product' => $product
        ];
        return view('admin.stok.show_product', $data);
    }
    public function getAllProduct()
    {
        $Product = Product::all();
        return response()->json($Product);
    }
    public function getAllStok()
    {
        $stok = ProductStok::all();
        return response()->json($stok);
    }
    public function getProductsDataTable(Request $request)
    {
        $query = Product::select(['id', 'name', 'unit', 'barcode', 'quantity_unit', 'photo', 'id_wirehouse', 'created_at', 'updated_at'])
            ->orderByDesc('id');

        if ($request->has('stok')) {
            $stokValue = $request->input('stok');

            if ($request->has('stok')) {

                if ($stokValue == 'Tersedia') {
                    $query->whereHas('product_stoks', function ($query) {
                        $query->selectRaw('COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN type = "Keluar" THEN quantity ELSE 0 END), 0) as available_quantity')
                            ->having('available_quantity', '>', 0);
                    });
                } elseif ($stokValue == 'Kosong') {
                    $query->whereHas('product_stoks', function ($query) {
                        $query->selectRaw('COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN type = "Keluar" THEN quantity ELSE 0 END), 0) as available_quantity')
                            ->having('available_quantity', '<=', 0);
                    });
                }
            }
        }

        if ($request->has('wirehouse')) {
            $wirehouseId = $request->input('wirehouse');
            if ($wirehouseId !== '-') {
                $query->where('id_wirehouse', $wirehouseId);
            }
        }

        $products = $query->get();

        return Datatables::of($products)
            ->addColumn('produk', function ($product) {
                return '<strong>' . $product->name . '</strong><br><span class="text-muted"> Isi ' . $product->quantity_unit . ' Pcs /' . $product->unit . '</span>';
            })
            ->addColumn('action', function ($product) {
                return view('admin.stok.components.product.actions_product', compact('product'));
            })
            ->addColumn('wirehouse', function ($product) {
                return '<strong>' . $product->wirehouse->name . '</strong><br><span class="text-muted">' . $product->wirehouse->address . '</span>';
            })
            ->addColumn('stok', function ($product) {
                return view('admin.stok.components.product.stok', compact('product'));
            })
            ->addColumn('expired', function ($product) {
                $stok = ProductStok::where('id_product', $product->id);

                if ($stok->count() != 0) {
                    $expiredHtml = '<ul style="list-style: none;padding:5px;">';

                    foreach ($stok->get() as $itemStok) {
                        if ($itemStok->type == 'Masuk') {
                            $stok_keluar = ProductStok::where('id_product', $product->id)
                                ->where('type', 'Keluar')
                                ->where('expired_date', $itemStok->expired_date)
                                ->sum('quantity');

                            if ($itemStok->quantity - $stok_keluar != 0) {
                                if ($itemStok->expired_date <= date('Y-m-d')) {
                                    $expiredHtml .= '<li class="text-danger"><b>' . ($itemStok->quantity - $stok_keluar) . '</b> ' . $product->unit . ' Kadaluarsa</li>';
                                } elseif ($itemStok->expired_date <= date('Y-m-d', strtotime('+3 months'))) {
                                    $expiredHtml .= '<li class="text-warning"><b>' . ($itemStok->quantity - $stok_keluar) . '</b> ' . $product->unit . ' Akan Kadaluarsa</li>';
                                } else {
                                    $expiredHtml .= '<li class="text-success"><b>' . ($itemStok->quantity - $stok_keluar) . '</b> ' . $product->unit . ' Aman</li>';
                                }
                            } else {
                                $expiredHtml .= '<span class="text-muted">Stok Kosong</span>';
                            }
                        }
                    }

                    $expiredHtml .= '</ul>';
                } else {
                    $expiredHtml = '<span class="text-muted">Belum ada stok</span>';
                }

                return $expiredHtml;
            })

            ->rawColumns(['produk', 'action', 'wirehouse', 'stok', 'expired'])
            ->make(true);
    }
    public function getStoksDataTable(Request $request)
    {
        $query = ProductStok::select(['id', 'id_product', 'quantity', 'type', 'expired_date', 'description', 'id_user', 'created_at', 'updated_at'])
            ->orderByDesc('id')
            ->with(['product', 'user']);

        if ($request->has('user')) {
            $userId = $request->input('user');
            if ($userId !== '-') {
                $query->where('id_user', $userId);
            }
        }
        if ($request->has('type')) {
            $type = $request->input('type');
            if ($type !== '-') {
                if ($type == 'Stok Masuk') {
                    $query->where('type', 'Masuk');
                } else {
                    $query->where('type', 'Keluar');
                }
            }
        }
        if ($request->has('expired')) {
            $expired = $request->input('expired');
            if ($expired !== '-') {
                if ($expired == 'Telah Kadaluarsa') {
                    $query->where('expired_date', '<=', date('Y-m-d'));
                } elseif ($expired == 'Akan Kadaluarsa') {
                    $query->where('expired_date', '<=', date('Y-m-d', strtotime('+3 month')));
                } elseif ($expired == 'Belum Kadaluarsa') {
                    $query->where('expired_date', '>', date('Y-m-d'));
                    $query->where('expired_date', '>', date('Y-m-d', strtotime('+3 month')));
                }
            }
        }
        if ($request->has('from-date') && $request->has('to-date')) {
            $fromDate = $request->input('from-date');
            $toDate = $request->input('to-date');
            if ($fromDate != '' && $toDate != '') {
                $query->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate);
            }
        }
        $stoks = $query->get();

        return Datatables::of($stoks)
            ->addColumn('produk', function ($stok) {
                $typeClass = ($stok->type == "Masuk") ? "primary" : "danger";
                return "<strong class='text-$typeClass'>$stok->product->name</strong><br><span class='text-muted'>$stok->product->wirehouse->name</span>";
            })
            ->addColumn('total', function ($stok) {
                $typeClass = ($stok->type == "Masuk") ? "primary" : "danger";
                return "<strong class='h4 text-$typeClass'>$stok->quantity</strong><br><span class='badge bg-label-$typeClass'>Stok $stok->type</span>";
            })
            ->addColumn('user', function ($stok) {
                $typeClass = ($stok->type == "Masuk") ? "primary" : "danger";
                return "<strong>" . $stok->user->name . "</strong><br><small class='text-$typeClass'>" . $stok->created_at->format('d F Y , H:i:s') . "</small>";
            })
            ->addColumn('action', function ($stok) {
                return view('admin.stok.components.stok.actions_stok', compact('stok'));
            })
            ->addColumn('warning', function ($stok) {
                return view('admin.stok.components.stok.warning', compact('stok'));
            })
            ->rawColumns(['produk', 'action', 'total', 'user', 'warning'])
            ->make(true);
    }

    public function getProductDetailDataTable($id)
    {
        $stoks = ProductStok::select(['id', 'id_product', 'quantity', 'type', 'expired_date', 'id_user', 'created_at', 'updated_at'])->orderByDesc('id')->where('id_product', $id)->with(['product', 'user']);

        return Datatables::of($stoks)
            ->addColumn('produk', function ($stok) {
                return '<strong class="text-' . ($stok->type == "Masuk" ? "primary" : "danger") . '" >' . $stok->product->name . '</strong><br><span class="text-muted"> ' . $stok->product->wirehouse->name .  '</span>';
            })
            ->addColumn('total', function ($stok) {
                return '<strong class="h4 text-' . ($stok->type == "Masuk" ? "primary" : "danger") . '">' . $stok->quantity . '</strong><br><span class="badge bg-label-' . ($stok->type == "Masuk" ? "primary" : "danger") . '">Stok ' . $stok->type .  '</span>';
            })
            ->addColumn('user', function ($stok) {
                return '<strong>' . $stok->user->name . '</strong><br><small class="text-' . ($stok->type == "Masuk" ? "primary" : "danger") . '"> ' . $stok->created_at->format('d F Y , H:i:s') .  '</small>';
            })
            ->addColumn('warning', function ($stok) {
                return view('admin.stok.components.stok.warning', compact('stok'));;
            })
            ->rawColumns(['produk', 'total', 'user', 'warning'])
            ->make(true);
    }
    public function store_product(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'quantity_unit' => 'required|string|max:20',
            'id_wirehouse' => 'required|string|max:20',
            'barcode' => 'string|max:20',
        ]);

        $ProductData = [
            'name' => $request->input('name'),
            'unit' => $request->input('unit'),
            'sub_unit' => $request->input('sub_unit'),
            'barcode' => $request->input('barcode'),
            'quantity_unit' => $request->input('quantity_unit'),
            'id_wirehouse' => $request->input('id_wirehouse'),
        ];

        if ($request->filled('id')) {
            $Product = Product::find($request->input('id'));
            if (!$Product) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $Product->update($ProductData);
            $message = 'Berhasil mengedit data';
        } else {
            Product::create($ProductData);
            $message = 'Berhasil menambah data';
        }

        return response()->json(['message' => $message]);
    }
    public function store_stok(Request $request)
    {
        $request->validate([
            'id_product' => 'required|string|max:255',
            'quantity' => 'required|string|max:20',
            'expired_date' => 'required|string|max:20',
            'description' => 'string|max:20',
        ]);

        $StokData = [
            'id_product' => $request->input('id_product'),
            'quantity' => $request->input('quantity'),
            'expired_date' => $request->input('expired_date'),
            'description' => $request->input('description'),
            'id_user' => Auth::user()->id
        ];

        if ($request->filled('id')) {
            $Stok = ProductStok::find($request->input('id'));
            if (!$Stok) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $Stok->update($StokData);
            $message = 'Berhasil mengedit data';
        } else {
            ProductStok::create($StokData);
            $message = 'Berhasil menambah data';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy_product($id)
    {
        $Product = Product::find($id);

        if (!$Product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $Product->delete();

        return response()->json(['message' => 'Berhasil menghapus data']);
    }
    public function edit_product($id)
    {
        $Product = Product::find($id);

        if (!$Product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($Product);
    }
    public function destroy_stok($id)
    {
        $Stok = ProductStok::find($id);

        if (!$Stok) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $Stok->delete();

        return response()->json(['message' => 'Berhasil menghapus data']);
    }
    public function edit_stok($id)
    {
        $ProducStok = ProductStok::where('id', $id)->with(['product'])->first();

        if (!$ProducStok) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($ProducStok);
    }
}

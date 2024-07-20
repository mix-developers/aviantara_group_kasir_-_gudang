<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderWirehouse;
use App\Models\OrderWirehousePayment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Pelanggan',
        ];
        return view('admin.customers.index', $data);
    }
    public function getAll()
    {
        $customer = customer::all();
        return response()->json($customer);
    }
    public function getCustomer($id)
    {
        $customer = customer::find($id);
        return response()->json($customer);
    }
    public function show($id)
    {
        $customer = Customer::find($id);
        $data = [
            'title' => 'Pelanggan : ' . $customer->name,
            'customer' => $customer
        ];
        return view('admin.customers.show', $data);
    }
    public function getCustomersDataTable()
    {
        $customers = Customer::select(['id', 'name', 'phone', 'address_home', 'address_company', 'created_at', 'updated_at'])
            ->orderByDesc('id');

        return Datatables::of($customers)
            ->addColumn('action', function ($customer) {
                return view('admin.customers.components.actions', compact('customer'));
            })
            ->addColumn('phone', function ($customer) {
                return '<a href="https://wa.me/' . $customer->phone . '" target="__blank">' . $customer->phone . '</a>';
            })
            ->addColumn('home', function ($customer) {
                return $customer->address_home;
            })
            ->addColumn('company', function ($customer) {
                return $customer->address_company;
            })
            ->rawColumns(['action', 'phone', 'home', 'company'])
            ->make(true);
        // return Datatables::of(Customer::select(['id', 'name', 'phone', 'address_home', 'address_company', 'created_at', 'updated_at'])
        //     ->orderByDesc('id'))
        //     ->addColumn('action', function ($customer) {
        //         return view('admin.customers.components.actions', compact('customer'));
        //     })
        //     ->addColumn('phone', function ($customer) {
        //         return '<a href="https://wa.me/' . $customer->phone . '" target="__blank">' . $customer->phone . '</a>';
        //     })
        //     ->addColumn('home', function ($customer) {
        //         return Str::limit($customer->address_home, 10);
        //     })
        //     ->addColumn('company', function ($customer) {
        //         return Str::limit($customer->address_company, 10);
        //     })
        //     ->rawColumns(['action', 'phone', 'home', 'company'])
        //     ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_home' => 'required|string',
        ]);

        $customerData = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address_home' => $request->input('address_home'),
            'address_company' => $request->input('address_company'),
        ];

        if ($request->filled('id')) {
            $customer = Customer::find($request->input('id'));
            if (!$customer) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $customer->update($customerData);
            $message = 'Berhasil mengedit data';
        } else {
            Customer::create($customerData);
            $message = 'Berhasil menambah data';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $customers = Customer::find($id);

        if (!$customers) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $customers->delete();

        return response()->json(['message' => 'Berhasil menghapus data']);
    }
    public function edit($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }

        return response()->json($customer);
    }
    public function getCustomersDataTableDetail($id)
    {
        $OrderWirehouse = OrderWirehouse::orderByDesc('id')->with(['customer', 'product', 'wirehouse'])->where('id_customer', $id)->get();

        return DataTables::of($OrderWirehouse)
            ->addColumn('action', function ($OrderWirehouse) {
                return view('admin.customers.components.actions_detail', compact('OrderWirehouse'));
            })
            ->addColumn('date', function ($OrderWirehouse) {
                return $OrderWirehouse->created_at->format('d F Y');
            })
            ->addColumn('total_fee_text', function ($OrderWirehouse) {
                return 'Rp ' . number_format($OrderWirehouse->total_fee);
            })
            ->addColumn('additional_fee_text', function ($OrderWirehouse) {
                return 'Rp ' . number_format($OrderWirehouse->additional_fee);
            })
            ->addColumn('delivery_text', function ($OrderWirehouse) {
                $badge =  ($OrderWirehouse->delivery == 1 ? 'bg-label-success' : 'bg-label-danger');
                return '<span class="badge ' . $badge . '">' . ($OrderWirehouse->delivery == 1 ? 'Ya' : 'Tidak') . '</span>';
            })
            ->addColumn('wirehouse', function ($OrderWirehouse) {
                return '<strong>' . $OrderWirehouse->wirehouse->name . '</strong><br><span class="text-muted">' . $OrderWirehouse->wirehouse->address . '</span>';
            })
            ->addColumn('tagihan', function ($OrderWirehouse) {
                $check_payment = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');
                if ($check_payment < $OrderWirehouse->total_fee) {
                    $total = $OrderWirehouse->total_fee - $check_payment;
                    $terbayar = '<br><small class="text-primary">Terbayar : Rp ' . number_format($check_payment) . '</small>';
                    $sisa = '<br><small class="text-danger">Sisa : Rp ' . number_format($total) . '</small>';
                } else {
                    $terbayar = '';
                    $sisa = '';
                }
                return 'Rp ' . number_format($OrderWirehouse->total_fee) . $terbayar . $sisa;
            })
            ->addColumn('payment', function ($OrderWirehouse) {
                $check_payment = OrderWirehousePayment::where('id_order_wirehouse', $OrderWirehouse->id)->sum('paid');
                if ($check_payment <= 0) {
                    $payment = 'Menunggu Pembayaran';
                    $color = 'text-danger';
                } elseif ($check_payment < $OrderWirehouse->total_fee) {
                    $payment = 'Proses Pencicilan';
                    $color = 'text-warning';
                } else {
                    $payment = 'Lunas';
                    $color = 'text-primary';
                }
                return '<strong class="' . $color . '">' . $payment . '</strong>';
            })
            ->rawColumns(['action', 'total_fee_text', 'additional_fee_text', 'delivery_text', 'wirehouse', 'tagihan', 'payment', 'date'])
            ->make(true);
    }
}

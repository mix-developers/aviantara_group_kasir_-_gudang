<?php

namespace App\Http\Controllers;

use App\Models\Kios;
use Illuminate\Http\Request;

class KiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.kios.index',['title' => 'Kios']);
    }

    public function getKiosDataTable()
    {
        //APA YANG MO DITAMPILKAN DI KIOS??
        $customers = Kios::select(['id', 'name', 'phone', 'address_home', 'address_company', 'created_at', 'updated_at'])
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kios  $kios
     * @return \Illuminate\Http\Response
     */
    public function show(Kios $kios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kios  $kios
     * @return \Illuminate\Http\Response
     */
    public function edit(Kios $kios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kios  $kios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kios $kios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kios  $kios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kios $kios)
    {
        //
    }
}

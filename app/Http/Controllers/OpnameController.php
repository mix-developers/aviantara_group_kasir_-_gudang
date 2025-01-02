<?php

namespace App\Http\Controllers;

use App\Models\Opname;
use App\Models\OpnameItem;
use App\Models\OpnameSchedule;
use App\Models\Product;
use App\Models\Wirehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class OpnameController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Opname',
        ];
        return view('admin.opname.index', $data);
    }
    public function wirehouse_opname($id_wirehouse)
    {
        $data = [
            'title' => 'Data Opname per-bulan',
            'wirehouse' => Wirehouse::find($id_wirehouse)
        ];
        return view('admin.opname.wirehouse_opname', $data);
    }

    public function getMonths(Request $request)
    {
        $startDate = Carbon::now()->subYear(); // Mulai dari 1 tahun yang lalu
        $endDate = Carbon::now(); // Hingga bulan saat ini

        $months = collect();
        while ($startDate <= $endDate) {
            $isCurrentMonth = $startDate->month === Carbon::now()->month && $startDate->year === Carbon::now()->year;
            $buttonClass = $isCurrentMonth ? 'btn-primary' : 'btn-danger btn-sm';
            $text = $isCurrentMonth ? 'Opname!!!' : '<i class="bx bxs-file-pdf"></i> Download';
            $onCLick = $isCurrentMonth ? 'opnameWirehouse(' . $startDate->month . ',' . $startDate->year . ')' : 'viewWirehouse(' . $startDate->month . ',' . $startDate->year . ')';

            $latestOpname = OpnameItem::where('id_wirehouse', $request->input('wirehouse'))
                ->where('month', $startDate->month)
                ->where('year', $startDate->year)
                ->latest()
                ->first();
            $countProduct = Product::where('id_wirehouse', $request->input('wirehouse'))->count();
            $countItem = OpnameItem::where('id_wirehouse', $request->input('wirehouse'))
                ->where('month', $startDate->month)
                ->where('year', $startDate->year)
                ->count();

            $status_opname = '-';

            if ($countItem > 0 && $countItem < $countProduct) {
                $status_opname = '<span class="badge bg-label-warning">Sebagian</span>';
            } elseif ($countItem == $countProduct) {
                $status_opname = '<span class="badge bg-label-primary">Sudah</span>';
            } else {
                $status_opname = '<span class="badge bg-label-danger">Belum</span>';
            }
            $months->push([
                'id' => $startDate->format('Ym'),
                'month' => $startDate->format('F'),
                'year' =>  $startDate->year,
                'tanggal' => $latestOpname ? $latestOpname->updated_at->format('d F Y') : '<span class="badge bg-label-danger">Belum</span>',
                'status' =>  $status_opname,
                'action' => '<button class="btn ' . $buttonClass . '" type="button" onclick="' . $onCLick . '">' . $text . '</button>',
            ]);

            $startDate->addMonth(); // Tambah 1 bulan
        }

        // Urutkan data berdasarkan ID (tahun-bulan)
        $months = $months->sortBy('month')->values(); // Mengurutkan dan mereset index

        return DataTables::of($months)
            ->rawColumns(['status', 'action', 'tanggal'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_wirehouse' => 'required',
            'date_schedule' => 'required',
        ]);

        $scheduleData = [
            'id_wirehouse' => $request->input('id_wirehouse'),
            'date_schedule' => $request->input('date_schedule'),
        ];

        $checkSchedule = OpnameSchedule::where('id_wirehouse', $request->input('id_wirehouse'))
            ->first();
        if ($checkSchedule) {
            $checkSchedule->update($scheduleData);
            $message = 'Berhasil memperbarui jadwal';
        } else {

            OpnameSchedule::create($scheduleData);
            $message = 'Berhasil membuat jadwal';
        }


        return response()->json(['message' => $message]);
    }
    public function storeOpnameItem(Request $request)
    {
        // Validasi data yang diterima dari request
        $request->validate([
            'id_wirehouse' => 'required',
            'id_product' => 'required',
            'id_user' => 'required',
            'qty_real' => 'required|integer',

        ]);

        $stok_product = Product::getStok($request->input('id_product'));
        $stok_product_retail = Product::getStokRetail($request->input('id_product'));
        // Data yang akan di-insert atau di-update
        $opnameData = [
            'id_wirehouse' => $request->input('id_wirehouse') ?? Auth::user()->id_wirehouse,
            'id_product' => $request->input('id_product'),
            'id_user' => Auth::id(),
            'qty' => $stok_product,
            'qty_retail' => $stok_product_retail,
            'qty_real' => $request->input('qty_real'),
            'qty_real_retail' => $request->input('qty_real_retail'),
            'description' => $request->input('description', null),
            'month' => $request->input('month') ?? Carbon::now()->month,
            'year' => $request->input('year') ?? Carbon::now()->year,
            'selisih' => $request->input('qty_real') - $stok_product,
            'selisih_retail' => $request->input('qty_real_retail') - $stok_product_retail,
        ];

        // Cek apakah data opname sudah ada
        $existingOpname = OpnameItem::where('id_wirehouse', $request->input('id_wirehouse'))
            ->where('id_product', $request->input('id_product'))
            ->where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)
            ->first();

        if ($existingOpname) {
            // Jika sudah ada, update data opname
            $existingOpname->update($opnameData);
            $message = 'Berhasil memperbarui data opname';
        } else {
            // Jika belum ada, buat data baru
            OpnameItem::create($opnameData);
            $message = 'Berhasil menambahkan data opname';
        }

        return response()->json(['message' => $message]);
    }
    public function pdf_wirehouse($month, $year, $id_wirehouse)
    {
        $data = Product::where('id_wirehouse', $id_wirehouse)->orderBy('id', 'DESC')->get();
        $opnameItem = OpnameItem::where('id_wirehouse', $id_wirehouse)
            ->where('month', $month)
            ->where('year', $year);
        // dd($opnameItem->get());
        $wirehouse = Wirehouse::find($id_wirehouse);
        $pdf =  \PDF::loadView('admin.opname.report.pdf_opname_wirehouse', [
            'data' => $data,
            'month' => $month ?? '',
            'year' => $year ?? '',
            'wirehouse' => $wirehouse,
            'opname_item' => $opnameItem,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan Opname Gudang ' . $wirehouse->name . ' - ' . date('Y-m-d H:i') . '.pdf');
    }
}

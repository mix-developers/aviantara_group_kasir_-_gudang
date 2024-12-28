<?php

namespace App\Http\Controllers;

use App\Models\Opname;
use App\Models\OpnameSchedule;
use App\Models\Wirehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
            $buttonClass = $isCurrentMonth ? 'btn-danger' : 'btn-secondary btn-sm';
            $text = $isCurrentMonth ? 'Opname !!!' : 'Lihat Hasil';
            $onCLick = $isCurrentMonth ? 'opnameWirehouse(' . $startDate->month . ',' . $startDate->year . ')' : 'viewWirehouse(' . $startDate->month . ',' . $startDate->year . ')';

            $months->push([
                'id' => $startDate->format('Ym'),
                'month' => $startDate->format('F'),
                'year' =>  $startDate->year,
                'tanggal' => '-',
                'status' => 'Noting',
                'action' => '<button class="btn ' . $buttonClass . '" type="button" onclick="' . $onCLick . '">' . $text . '</button>',
            ]);

            $startDate->addMonth(); // Tambah 1 bulan
        }

        // Urutkan data berdasarkan ID (tahun-bulan)
        $months = $months->sortBy('month')->values(); // Mengurutkan dan mereset index

        return DataTables::of($months)->make(true);
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
}
<?php

namespace App\Http\Controllers;

use App\Models\DataBotol;
use Illuminate\Support\Facades\DB;
use App\Models\ActivitiesModel;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBotol = DataBotol::count();
        $totalBotolKosong = DataBotol::where('status_isi', 'kosong')->count();
        $totalBotolIsi = DataBotol::where('status_isi', 'isi')->count();
        $botolKosongs = DataBotol::where('status_isi', 'kosong')
            ->whereIn('id', function ($q) {
                $q->from('detail_transaksi_isi_botol as d')
                    ->select('d.botol_id')
                    ->whereIn('d.id', function ($qq) {
                        $qq->from('detail_transaksi_isi_botol')
                            ->select(DB::raw('MAX(id)'))
                            ->groupBy('botol_id');
                    })
                    ->where('d.status_kirim', 1);
            })
            ->get();
        $botolMasukPabrik = DataBotol::where('status_isi', 'masuk pabrik')
            ->with('details.transaksi') // ini sekarang valid
            ->get();
        $activities = ActivitiesModel::all();
        return view('dashboard', [
            'totalBotol' => $totalBotol,
            'totalBotolKosong' => $totalBotolKosong,
            'totalBotolIsi' => $totalBotolIsi,
            'botolKosongs' => $botolKosongs,
            'botolMasukPabrik' => $botolMasukPabrik,
            'activities' => $activities,
        ]);
    }

    public function data()
    {
        $query = ActivitiesModel::query();


        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d/m/y');
            })
            ->make(true);
    }
    //
}

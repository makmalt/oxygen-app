<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBotol;
use Illuminate\Support\Facades\DB;

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
        return view('dashboard', [
            'totalBotol' => $totalBotol,
            'totalBotolKosong' => $totalBotolKosong,
            'totalBotolIsi' => $totalBotolIsi,
            'botolKosongs' => $botolKosongs,
        ]);
    }
    //
}

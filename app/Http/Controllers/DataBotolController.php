<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBotol;
use App\Models\DataPinjaman;
use App\Models\JenisBotol;

class DataBotolController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = DataBotol::query();

        if ($request->filled('status')) {
            $status = strtolower($request->get('status'));

            if ($status === 'dipinjam') {
                $query->where('status_pinjaman', 1);
            } elseif ($status === 'sudah kembali') {
                $query->where('status_pinjaman', 0);
            }
        }

        $data_botols = $query->get();
        return view('data_botol.index', compact('data_botols'));
    }

    public function create()
    {
        $jenis = JenisBotol::orderBy('nama_jenis')->get(['id', 'nama_jenis']);
        return view('data_botol.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        DataBotol::create($request->all());
        return redirect()->route('data_botol.index');
    }

    public function edit($id)
    {
        $data_botol = DataBotol::find($id);
        return view('data_botol.edit', compact('data_botol'));
    }

    public function update(Request $request, $id)
    {
        $data_botol = DataBotol::find($id);
        $data_botol->update($request->all());
        return redirect()->route('data_botol.index');
    }

    public function destroy($id)
    {
        $data_botol = DataBotol::find($id);
        $data_botol->delete();
        return redirect()->route('data_botol.index');
    }

    public function show($id)
    {
        $data_botol = DataBotol::with([
            'pinjaman',
            'pinjamanAktif'
        ])->findOrFail($id);

        $riwayat = $data_botol->pinjaman()
            ->with('pelanggan')
            ->orderBy('tanggal_pinjaman', 'desc')
            ->get();

        return view('data_botol.show', [
            'data_botol'   => $data_botol,
            'riwayat' => $riwayat
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        //
        $status = DataBotol::findOrFail($id);
        $status->update(['status_pinjaman' => $request->status_pinjaman]);
        return redirect()->back()->with('success', 'Status "' . $status->status . '" berhasil diperbarui');
    }
}

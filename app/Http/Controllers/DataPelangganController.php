<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPelanggan;

class DataPelangganController extends Controller
{
    //
    public function index()
    {
        $data_pelanggans = DataPelanggan::all();
        return view('data_pelanggan.index', compact('data_pelanggans'));
    }
    public function create()
    {
        return view('data_pelanggan.create');
    }
    public function store(Request $request)
    {
        DataPelanggan::create($request->all());
        return redirect()->route('data_pelanggan.index');
    }
    public function edit($id)
    {
        $data_pelanggan = DataPelanggan::find($id);
        return view('data_pelanggan.edit', compact('data_pelanggan'));
    }
    public function update(Request $request, $id)
    {
        $data_pelanggan = DataPelanggan::find($id);
        $data_pelanggan->update($request->all());
        return redirect()->route('data_pelanggan.index');
    }

    public function destroy($id)
    {
        $data_pelanggan = DataPelanggan::find($id);
        $data_pelanggan->delete();
        return redirect()->route('data_pelanggan.index');
    }

    public function show($id)
    {
        $data_pelanggan = DataPelanggan::with('pinjamBotol')
            ->findOrFail($id);


        $riwayat = $data_pelanggan->pinjamBotol()
            ->with('botol')
            ->orderBy('tanggal_pinjaman', 'desc')
            ->get();

        return view('data_pelanggan.show', [
            'data_pelanggan' => $data_pelanggan,
            'riwayat' => $riwayat
        ]);
    }
}

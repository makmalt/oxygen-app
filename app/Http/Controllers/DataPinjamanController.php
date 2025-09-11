<?php

namespace App\Http\Controllers;

use App\Models\DataBotol;
use App\Models\DataPinjaman;
use App\Models\PenanggungJawab;
use Illuminate\Http\Request;
use App\Models\DataPelanggan;
use Illuminate\Support\Facades\DB;

class DataPinjamanController extends Controller
{
    //
    public function index()
    {
        $data_pinjamans = DataPinjaman::with(['botol', 'pelanggan'])->get();
        return view('data_pinjaman.index', compact('data_pinjamans'));
    }
    public function create()
    {
        $data_botols = DataBotol::all();
        $data_pelanggans = DataPelanggan::all();
        $penanggung_jawabs = PenanggungJawab::all();
        return view('data_pinjaman.create')->with([
            'data_botols' => $data_botols,
            'data_pelanggans' => $data_pelanggans,
            'penanggung_jawabs' => $penanggung_jawabs,
        ]);
    }
    public function store(Request $request)
    {
        // Validasi input utama + field opsional untuk botol sebelumnya
        $validated = $request->validate([
            'nomor_botol' => 'required|exists:data_botol,id',
            'nama_pelanggan' => 'required|exists:data_pelanggan,id',
            'tanggal_pinjaman' => 'required|date',
            'tanggal_pengembalian' => 'nullable|date',
            'nomor_botol_sebelumnya' => 'nullable|exists:data_botol,id',
            'penanggung_jawab_id' => 'nullable|exists:penanggung_jawab,id',
        ]);

        DB::transaction(function () use ($validated) {
            // Jika user memilih botol sebelumnya dan mengisi tanggal_pengembalian,
            // arahkan tanggal_pengembalian ke pinjaman aktif botol sebelumnya
            if (!empty($validated['nomor_botol_sebelumnya']) && !empty($validated['tanggal_pengembalian'])) {
                $pinjamanSebelumnya = DataPinjaman::where('nomor_botol', $validated['nomor_botol_sebelumnya'])
                    ->whereNull('tanggal_pengembalian')
                    ->orderBy('tanggal_pinjaman', 'desc')
                    ->first();

                if ($pinjamanSebelumnya) {
                    $pinjamanSebelumnya->tanggal_pengembalian = $validated['tanggal_pengembalian'];
                    $pinjamanSebelumnya->save();
                }
            }

            // Buat pinjaman baru untuk botol saat ini tanpa tanggal_pengembalian (masih dipinjam)
            DataPinjaman::create([
                'nomor_botol' => $validated['nomor_botol'],
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'tanggal_pinjaman' => $validated['tanggal_pinjaman'],
                'tanggal_pengembalian' => null,
                'penanggung_jawab_id' => $validated['penanggung_jawab_id'] ?? null,
            ]);
        });

        return redirect()->route('data_pinjaman.index');
    }
    public function edit($id)
    {
        $data_pinjaman = DataPinjaman::find($id);
        return view('data_pinjaman.edit', compact('data_pinjaman'));
    }
    public function update(Request $request, $id)
    {
        $data_pinjaman = DataPinjaman::find($id);
        $data_pinjaman->update($request->all());
        return redirect()->route('data_pinjaman.index');
    }
    public function destroy($id)
    {
        $data_pinjaman = DataPinjaman::find($id);
        $data_pinjaman->delete();
        return redirect()->route('data_pinjaman.index');
    }
    public function show($id)
    {
        $data_pinjaman = DataPinjaman::find($id);
        return view('data_pinjaman.show', compact('data_pinjaman'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'tanggal_pengembalian' => 'nullable|date',
        ]);

        $dataPinjaman = DataPinjaman::findOrFail($id);
        $dataPinjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
        $dataPinjaman->save();

        return redirect()->back()->with('success', 'Tanggal pengembalian berhasil diperbarui.');
    }

    public function getPelangganByBotol($botolId)
    {
        $botol = DataBotol::findOrFail($botolId);
        // Filter berdasarkan nomor_botol (string), urutkan terbaru, ambil maks 3
        $pinjaman = DataPinjaman::where('nomor_botol', $botol->id)
            ->with('pelanggan')
            ->orderBy('tanggal_pinjaman', 'desc')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => optional($item->pelanggan)->id,
                    'nama' => optional($item->pelanggan)->nama,
                    'alamat' => optional($item->pelanggan)->alamat,
                    'no_hp' => optional($item->pelanggan)->no_hp,
                    'tanggal_pinjaman_terakhir' => optional($item->tanggal_pinjaman)->format('d F Y'),
                    'status_pinjaman_terakhir' => is_null($item->tanggal_pengembalian) ? 'Dipinjam' : 'Sudah Kembali'
                ];
            })
            ->filter(function ($row) {
                return !empty($row['id']);
            })
            ->values();

        return response()->json($pinjaman);
    }
}

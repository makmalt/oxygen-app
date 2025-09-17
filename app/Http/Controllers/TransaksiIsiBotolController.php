<?php

namespace App\Http\Controllers;

use App\Models\DataBotol;
use App\Models\DetailTransaksiIsiBotol;
use App\Models\TransaksiIsiBotol;
use App\Models\DataSupplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiIsiBotolController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiIsiBotol::with(['details'])->orderByDesc('tanggal_isi')->get();
        return view('transaksi_isi_botol.index', compact('transaksis'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = trim((string) data_get($request->input('search'), 'value', ''));

        $baseQuery = TransaksiIsiBotol::query()
            ->withCount([
                'details as total_botol',
                'details as jumlah_kembali' => function ($q) {
                    $q->where('status_kirim', 1);
                },
            ]);

        $recordsTotal = (clone $baseQuery)->count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('tanggal_isi', 'like', "%$search%")
                    ->orWhere('supplier_id', 'like', "%$search%");
            });
        }

        // Ordering
        $orderColumnIndex = (int) data_get($request->input('order'), '0.column', 1);
        $orderDir = data_get($request->input('order'), '0.dir', 'desc');
        $columns = [0 => 'id', 1 => 'tanggal_isi', 2 => 'total_botol', 3 => 'jumlah_kembali'];
        $orderBy = $columns[$orderColumnIndex] ?? 'tanggal_isi';
        if (!in_array($orderDir, ['asc', 'desc'], true)) {
            $orderDir = 'desc';
        }

        $filteredQuery = (clone $baseQuery);
        $recordsFiltered = $filteredQuery->count();

        $rows = $baseQuery
            ->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(function ($trx) {
            $tanggal = \Illuminate\Support\Carbon::parse($trx->tanggal_isi)->format('d-m-Y');
            $total = (int) ($trx->total_botol ?? 0);
            $kembali = (int) ($trx->jumlah_kembali ?? 0);
            $aksi = route('transaksi_isi_botol.crosscheck', $trx->id);
            return [
                '',
                $tanggal,
                $total,
                "$kembali / $total",
                '<a class="btn btn-sm btn-outline-primary" href="' . e($aksi) . '">Crosscheck</a>',
            ];
        })->all();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function create()
    {
        $botolCount = DataBotol::count();
        $botols = $botolCount > 10
            ? collect()
            : DataBotol::orderBy('nomor_botol')->get(['id', 'nomor_botol', 'status_isi']);
        $suppliers = DataSupplier::orderBy('nama_supplier')->get(['id', 'nama_supplier']);
        return view('transaksi_isi_botol.create', compact('botols', 'suppliers', 'botolCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_isi' => 'required|date',
            'supplier_id' => 'nullable|exists:data_supplier,id',
            'botol_ids' => 'required|array|min:1',
            'botol_ids.*' => 'required|exists:data_botol,id',
        ]);

        $transaksi = DB::transaction(function () use ($validated) {
            $trx = TransaksiIsiBotol::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'tanggal_isi' => $validated['tanggal_isi'],
            ]);

            foreach ($validated['botol_ids'] as $botolId) {
                DetailTransaksiIsiBotol::create([
                    'transaksi_id' => $trx->id,
                    'botol_id' => $botolId,
                    'status_kirim' => 0, // dikirim ke pabrik
                ]);
                // Update status_isi botol menjadi 'masuk pabrik'
                DataBotol::where('id', $botolId)->update(['status_isi' => 'masuk pabrik']);
            }

            return $trx;
        });

        return redirect()->route('transaksi_isi_botol.crosscheck', $transaksi->id)
            ->with('success', 'Transaksi pengiriman dibuat. Lakukan crosscheck saat menerima.');
    }

    public function crosscheck(int $id)
    {
        $transaksi = TransaksiIsiBotol::with(['details.botol'])->findOrFail($id);
        return view('transaksi_isi_botol.crosscheck', compact('transaksi'));
    }

    public function crosscheckStore(Request $request, int $id)
    {
        $transaksi = TransaksiIsiBotol::with(['details'])->findOrFail($id);
        $validated = $request->validate([
            'received_ids' => 'nullable|array',
            'received_ids.*' => 'required|exists:detail_transaksi_isi_botol,id',
        ]);

        DB::transaction(function () use ($transaksi, $validated) {
            $received = collect($validated['received_ids'] ?? []);

            foreach ($transaksi->details as $detail) {
                if ($received->contains($detail->id)) {
                    // diterima kembali dari pabrik: status_kirim=1 dan status_isi botol = 'terisi'
                    $detail->status_kirim = 1;
                    $detail->save();
                    $detail->botol?->update(['status_isi' => 'isi']);
                }
            }
        });

        return redirect()->route('transaksi_isi_botol.crosscheck', $transaksi->id)
            ->with('success', 'Crosscheck penerimaan diperbarui.');
    }

    public function botols(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->input('page', 1));
        $perPage = min(50, max(5, (int) $request->input('per_page', 20)));
        $q = trim((string) $request->input('q', ''));

        $query = DataBotol::query()->orderBy('nomor_botol');
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_botol', 'like', "%$q%")
                    ->orWhere('status_isi', 'like', "%$q%");
            });
        }

        $total = (clone $query)->count();
        $items = $query->skip(($page - 1) * $perPage)->take($perPage)
            ->get(['id', 'nomor_botol', 'status_isi'])
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'nomor_botol' => $b->nomor_botol,
                    'status_isi' => (string) $b->status_isi,
                ];
            })->all();

        $hasMore = ($page * $perPage) < $total;

        return response()->json([
            'data' => $items,
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'has_more' => $hasMore,
        ]);
    }
}

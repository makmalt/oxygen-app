<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPinjaman extends Model
{
    protected $table = 'data_pinjaman';

    protected $fillable = [
        'nomor_botol',
        'nama_pelanggan',
        'tanggal_pinjaman',
        'tanggal_pengembalian',
        'penanggung_jawab_id',
    ];

    protected $casts = [
        'tanggal_pinjaman' => 'date',
        'tanggal_pengembalian' => 'date',
    ];

    public function botol()
    {
        return $this->belongsTo(DataBotol::class, 'nomor_botol', 'id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(DataPelanggan::class, 'nama_pelanggan', 'id');
    }
    public function penanggungJawab()
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id', 'id');
    }

    public $timestamps = true;

    protected static function booted()
    {
        static::saved(function ($pinjaman) {
            // Kalau belum dikembalikan, set botol jadi dipinjam
            if (is_null($pinjaman->tanggal_pengembalian)) {
                $pinjaman->botol()->update(['status_pinjaman' => 1]);
            } else {
                // Kalau sudah dikembalikan, set botol jadi tersedia
                $pinjaman->botol()->update(['status_pinjaman' => 0]);
            }
        });
    }
}

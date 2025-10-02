<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBotol extends Model
{
    protected $table = 'data_botol';

    protected $fillable = [
        'nomor_botol',
        'uniq',
        'jenis_botol',
        'status_pinjaman',
        'status_isi'
    ];

    public function pinjaman()
    {
        return $this->hasMany(DataPinjaman::class, 'nomor_botol');
    }

    // Pinjaman aktif (belum dikembalikan)
    public function pinjamanAktif()
    {
        return $this->hasOne(DataPinjaman::class, 'nomor_botol')
            ->whereNull('tanggal_pengembalian');
    }

    public function details()
    {
        return $this->hasOne(DetailTransaksiIsiBotol::class, 'botol_id')->latestOfMany('id');
    }

    public $timestamps = true;
}

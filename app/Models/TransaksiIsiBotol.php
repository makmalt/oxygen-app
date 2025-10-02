<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiIsiBotol extends Model
{
    use HasFactory;

    protected $table = 'transaksi_isi_botol';

    protected $fillable = [
        'supplier_id',
        'tanggal_isi',
    ];

    protected $casts = [
        'tanggal_isi' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(DetailTransaksiIsiBotol::class, 'transaksi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiIsiBotol extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_isi_botol';

    protected $fillable = [
        'transaksi_id',
        'botol_id',
        'status_kirim',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiIsiBotol::class, 'transaksi_id');
    }

    public function botol()
    {
        return $this->belongsTo(DataBotol::class, 'botol_id');
    }
}

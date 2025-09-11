<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPelanggan extends Model
{
    protected $table = 'data_pelanggan';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
    ];

    public $timestamps = true;

    public function pinjamBotol()
    {
        return $this->hasMany(DataPinjaman::class, 'nama_pelanggan', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBotol extends Model
{
    use HasFactory;

    protected $table = 'jenis_botol';

    protected $fillable = [
        'nama_jenis',
    ];
}

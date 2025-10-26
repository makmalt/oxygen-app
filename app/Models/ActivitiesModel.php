<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesModel extends Model
{
    //
    protected $table = 'activities';
    protected $fillable = [
        'activityName',
        'no_botol',
        'description'
    ];
    public $timestamps = true;

    function botol()
    {
        return $this->belongsTo(DataBotol::class, 'no_botol', 'no_botol');
    }
}

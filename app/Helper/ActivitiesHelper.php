<?php

namespace App\Helper;

use App\Models\ActivitiesModel;

class ActivitiesHelper
{
    public static function activities($activity, $no_botol, $description = null)
    {
        ActivitiesModel::create([
            'activityName' => $activity,
            'no_botol' => $no_botol,
            'description' => $description,
        ]);
    }
    //
}

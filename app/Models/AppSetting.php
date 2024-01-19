<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    //
    protected $casts = [
        'opening_time' => 'datetime:H:i:k',
        'closing_time' => 'datetime:H:i:k',
    ];
}

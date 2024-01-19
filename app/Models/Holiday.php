<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Holiday extends Model
{
    use HasFactory;

    protected $casts = [
        'holiday_date' => 'date',
    ];
}

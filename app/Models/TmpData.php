<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmpData extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'order_data'
    ];
}

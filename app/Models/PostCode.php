<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCode extends Model
{
    use HasFactory;

    protected $fillable = ['zone_id', 'post_code'];

    public function zone()
    {
        return $this->belongsTo(Zone::class,'zone_id');
    }
}

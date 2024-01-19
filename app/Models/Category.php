<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
}

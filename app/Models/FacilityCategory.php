<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityCategory extends Model
{
    //
    public function service()
    {
        return $this->belongsTo(FacilityService::class,'fc_service_id');
    }

}

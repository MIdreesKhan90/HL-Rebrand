<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityService extends Model
{
    public function facility_categories()
    {
        return $this->hasMany(FacilityCategory::class,'id');
    }


}

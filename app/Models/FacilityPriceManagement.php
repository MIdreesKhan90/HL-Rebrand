<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityPriceManagement extends Model
{
    protected $fillable = ['center_name','service_name','sub_service_name','item_name','price'];

    public function center()
    {
        return $this->belongsTo(FacilityCenter::class,'center_name');
    }

    public function service()
    {
        return $this->belongsTo(FacilityService::class,'service_name');
    }

    public function category()
    {
        return $this->belongsTo(FacilityCategory::class,'sub_service_name');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_name');
    }



}

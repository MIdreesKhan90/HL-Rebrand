<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityOrder extends Model
{

    protected $fillable = [
        'id','user_id','order_id', 'order_date', 'customer_name','pickup_date','dropoff_date','service_list','customer_note','facility_note','itemization','job_title','prices','totalPrice','collected_by','bagsHangers','order_status','penalty','penalty_reason','job_status',
    ];

    public function facility()
    {
        return $this->belongsTo(FacilityCenter::class,'user_id');
    }

    public function driver(){
        return $this->belongsTo(DeliveryBoy::class,'collected_by');
    }
    public function getServiceListAttribute($value)
    {
        return explode(',', $value);
    }

    public function setServiceListAttribute($value)
    {
        $this->attributes['service_list'] = implode(',', $value);
    }
    // public function services() {
    //     return $this->hasMany('App\Models\VendorOrderService','order_id');
    // }

}

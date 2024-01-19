<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorOrder extends Model
{
    public function vendor()
    {
        return $this->belongsTo(HotelVendor::class,'vendor_id');
    }
    public function employee()
    {
        return $this->hasOne(HotelEmployee::class,'employee_id');
    }
}

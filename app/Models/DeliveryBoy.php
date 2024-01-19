<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryBoy extends Model
{
    protected $fillable = [
        'id', 'delivery_boy_name', 'phone_number','email','password','profile_picture','status','otp','fcm_token'
    ];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}

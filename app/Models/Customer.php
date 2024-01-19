<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // protected $attributes = [
    //     'fcm_token' => "website_user"
    // ];
    protected $fillable = [
        'id', 'customer_type', 'customer_name', 'uCompanyName', 'taxNumber', 'uFName','uLName','phone_code', 'phone_number','email','password','profile_picture','status','otp','fcm_token'
    ];
}

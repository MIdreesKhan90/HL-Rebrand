<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'customer_id', 'address', 'unique_id','city', 'country', 'postcode', 'status','manual_address','type'
    ];
}

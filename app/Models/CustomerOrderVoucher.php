<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderVoucher extends Model
{
    protected $fillable = [
        'customer_id', 'order_id', 'promo_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{


    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function orders(){
        return $this->belongsToMany('App\Order', 'App\OrderService', 'service_id','order_id');
    }
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
class OrderService extends Pivot
{
    public $timestamps = true;

    protected $table = 'order_services';
    protected $fillable = [
        'id', 'order_id', 'service_id','category_id'
    ];
    // public function service(){
    //     return $this->belongsTo(Service::class, 'service_id');
    // }
}

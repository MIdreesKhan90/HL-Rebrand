<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TodayTasks extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected static function booted()
    {
        static::addGlobalScope('task',function (Builder $builder) {
            $builder->where(fn($query) => $query->whereDate('pickup_date',Carbon::today())
                                                ->orWhereDate('delivery_date',Carbon::today()));
        });
    }
    protected $fillable = [
        'customer_id', 'address_id','pickup_date','pickup_time', 'delivery_date','delivery_time','delivered_by','payment_mode','order_id','other_requests','collection_instructions','delivery_instructions','status','stripe_response_id','promo_code','created_at','updated_at','order_type','facility_id'
    ];
    public function services(){
        return $this->belongsToMany('App\Models\Service', 'App\Models\OrderService','order_id', 'service_id')->withPivot('service_id','category_id');
    }
    public function sub_services(){
        return $this->belongsTo('App\Models\Category', 'App\Models\OrderService','order_id', 'category_id');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id');
    }

    public function address(){
        return $this->belongsTo('App\Models\Address');
    }

    protected $casts = [
        'pickup_date' => 'date',
        'delivery_date' => 'date',
    ];
}

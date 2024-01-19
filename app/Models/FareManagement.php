<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FareManagement extends Model
{
    protected $table = 'fare_managements';

    protected $fillable = [
        'service_id',
        'item_id',
        'type_id',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'service_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    public function type()
    {
        return $this->belongsTo(ItemType::class,'type_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
}

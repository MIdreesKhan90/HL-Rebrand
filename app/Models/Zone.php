<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['zone_location','zone_name','start_time','end_time','hours_interval','pickup_delivery_difference'];

    public function location() {
        return $this->belongsTo(ZoneLocation::class,'zone_location');
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }


    protected $casts = [
      'start_time' => 'datetime:H:i:s',
      'end_time'   => 'datetime:H:i:s'
    ];
}

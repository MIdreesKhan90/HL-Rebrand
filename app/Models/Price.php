<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class Price extends Model
{
    use HasFactory;
    public function getRouteKeyName() {
        return 'slug';
    }
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tags'];

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'description',
        'price_details',
        'service_overview',
        'service_options',
        'service_suitable',
        'service_not_include',
        'service_collection',
        'service_delivery',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'price_tags');
    }

    protected $casts = [
        'price_details' => FlexibleCast::class,
    ];
}

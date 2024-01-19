<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class Commercial extends Model
{
    use HasFactory;
    protected $fillable = [
        'banner_image','banner_heading','rank_heading','rank_text_copy','rank_review_link_label','rank_review_link_url','services_heading','services'
    ];

    protected $casts = [
        'services' => FlexibleCast::class,
    ];
}

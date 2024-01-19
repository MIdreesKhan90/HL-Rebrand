<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class HomePage extends Model
{
    use HasFactory;
    protected $table = 'home_page';
    protected $fillable = [
    'banner_image','banner_heading','rank_heading','rank_text_copy','rank_review_link_label','rank_review_link_url','facilities','how_we_work_heading','processes','how_we_work_link_label','how_we_work_link_url','services_heading','services','services_link_label','services_link_url','minimum_order_text',
    ];

    protected $casts = [
        'facilities' => FlexibleCast::class,
        'processes' => FlexibleCast::class,
        'services' => FlexibleCast::class,
    ];
}

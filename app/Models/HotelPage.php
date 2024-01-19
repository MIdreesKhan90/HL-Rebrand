<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class HotelPage extends Model
{
    use HasFactory;
    protected $table = 'hotel_page';
    protected $fillable = [
        'banner_image','banner_heading','text_copy','how_we_work_heading','processes','how_we_work_link_label','how_we_work_link_url','services_heading','services','services_link_label','services_link_url','minimum_order_text','faq_heading','faqs','faqs_link_label','faqs_link_url'
    ];

    protected $casts = [
        'processes' => FlexibleCast::class,
        'services' => FlexibleCast::class,
        'faqs' => FlexibleCast::class,
    ];
}

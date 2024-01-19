<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCard extends Model
{
    protected $fillable = [
        'id', 'customer_id', 'card_token', 'last_four','is_default', 'created_at','updated_at'
    ];
}

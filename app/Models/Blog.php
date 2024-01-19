<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','slug','title','featured_image','content','author_id','published_date','is_published'];

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class,'blog_category_pivot', 'blog_id', 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(AdminUser::class,'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'blog_tags');
    }

    protected $casts = [
        'published_date' => 'date',
    ];
}

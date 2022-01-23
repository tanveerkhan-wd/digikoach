<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedBlog extends Model
{
    protected $primaryKey = 'saved_blog_id';
    public $timestamps = true;

    public function blog()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id', 'blog_post_id');
    }
}

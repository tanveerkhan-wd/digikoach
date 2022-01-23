<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

use Auth;

class BlogPost extends Model
{
    protected $primaryKey = 'blog_post_id';

    protected $dates = ['created_date', 'updated_date'];

    //protected $attributes = ['is_saved'];
    protected $appends = ['is_saved'];

    public function getIsSavedAttribute()
    {
        return ($this->savedBlogs()->first() && (int)$this->savedBlogs()->first()->item_id > 0 ? true : false);
    }

    public function desc()
    {
        $language = App::getLocale();
        return $this->hasOne('App\Models\BlogPostDesc', 'blog_post_id', 'blog_post_id')->where('lang_code', $language);
    }

    public function savedBlogs()
    {
        $user = Auth::guard('api')->user();
        $ojbSavedBlog = $this->hasOne(UserSavedItem::class, 'item_type_id', 'blog_post_id')->where('item_type', 'BLOG');
        if($user){
            $ojbSavedBlog->where('user_id', $user->user_id);
        }

        return $ojbSavedBlog;
    }

    public function blog_post_desc()
    {
        return $this->hasMany(BlogPostDesc::class, 'blog_post_id', 'blog_post_id');
    }

    public function blog_category_id()
    {
        return $this->belongsTo(BlogCategoriesDesc::class, 'blog_category_id', 'blog_category_id')->where('lang_code', 'en');
    }
}

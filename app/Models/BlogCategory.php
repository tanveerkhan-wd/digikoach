<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $primaryKey = 'blog_category_id';
    
    public function desc()
	{
        $language = App::getLocale();
		return $this->hasOne('App\Models\BlogCategoriesDesc', 'blog_category_id', 'blog_category_id')->where('lang_code', $language);
    }

    public function blog_cat_desc()
    {
    	return $this->hasMany(BlogCategoriesDesc::class, 'blog_category_id', 'blog_category_id');
    }
}

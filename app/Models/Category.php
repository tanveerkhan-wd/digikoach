<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $primaryKey = "category_id";
	protected $fillable = ['category_id', 'parent_category', 'gk_ca ', 'icon_img'];

	public function desc()
	{
		$language = App::getLocale();
		return $this->hasOne('App\Models\CategoriesDesc', 'category_id', 'category_id')->where('lang_code', $language);
	}

    public function desc_en()
    {
        $language = 'en';
        return $this->hasOne('App\Models\CategoriesDesc', 'category_id', 'category_id')->where('lang_code', $language);
    }
	//each category might have multiple children
    public function category_desc() {
        return $this->hasMany(CategoriesDesc::class, 'category_id');
    }

    public function parent_category() {
        return $this->hasMany(CategoriesDesc::class, 'category_id','parent_category');
    }

	//each category might have one parent
    public function parent() {
        return $this->belongsTo(static::class, 'parent_category','category_id');
    }

    //each category might have multiple children
    public function children() {
        return $this->hasMany(static::class, 'parent_category');
    }

}

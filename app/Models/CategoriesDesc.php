<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriesDesc extends Model
{
   
    protected $primaryKey = "category_desc_id";
    public $timestamps = true;
    
	protected $fillable = array('category_desc_id','lang_code','category_id','name');

	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
   
}

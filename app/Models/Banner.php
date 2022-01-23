<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $primaryKey = "banner_id";
	protected $fillable = ['banner_id', 'sequence','banner_file'];

	public function desc()
	{
		$language = App::getLocale();
		return $this->hasOne('App\Models\BannerDesc', 'banner_id', 'banner_id')->where('lang_code', $language);;
    }

    public function banner_desc()
    {
    	return $this->hasMany('App\Models\BannerDesc', 'banner_id', 'banner_id');
    }
    
}

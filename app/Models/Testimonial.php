<?php

namespace App\Models;
use App;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $primaryKey = 'testimonial_id';
    
    public function testimonial_desc()
    {
    	return $this->hasMany(TestimonialDesc::class, 'testimonial_id', 'testimonial_id');	
    }

    public function test_desc()
	{
		$language = App::getLocale();
		return $this->hasOne(TestimonialDesc::class, 'testimonial_id', 'testimonial_id')->where('lang_code', $language);
	}

}

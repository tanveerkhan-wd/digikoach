<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerDesc extends Model
{
    protected $primaryKey = "banner_desc_id";
    public $timestamps = false;
    
    protected $fillable = array('banner_desc_id','lang_code','banner_id','Banner_name');
}

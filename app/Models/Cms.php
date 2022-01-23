<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    protected $primaryKey = 'cms_id';

    public function desc()
    {
        $language = App::getLocale();
        return $this->hasOne(CmsDesc::class, 'cms_id', 'cms_id')->where('lang_code', $language);
    }

    public function cms_desc()
    {
        return $this->hasMany(CmsDesc::class, 'cms_id', 'cms_id');
    }
}

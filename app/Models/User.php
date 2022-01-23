<?php

namespace App\Models;

use App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    protected $primaryKey = "user_id";
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $fillable = array('user_id', 'name', 'email', 'password', 'mobile_number', 'user_img', 'forgot_password_otp', 'user_type', 'user_status', 'is_mobile_verify', 'is_email_verify', 'email_notification', 'push_notification', 'deleted', 'created_at', 'updated_at', 'deleted_at');

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function user_level(){
        $language = App::getLocale();
        return $this->belongsTo(Category::class, 'user_fav_category', 'category_id');
    }

    /*
    * Category
    *
    */
    public function fav_category()
    {
        return $this->belongsTo(CategoriesDesc::class, 'user_fav_category', 'category_id')->where('lang_code', 'en');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'user_fav_category', 'category_id');
    }

    public function user_category()
    {
        return $this->hasMany(UserCategory::class, 'user_id', 'user_id');
    }
}

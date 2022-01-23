<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Doubt extends Model
{
    protected $primaryKey = 'doubt_id';

    protected $appends = ['is_saved', 'is_up_voted'];

    public function category()
    {
        return $this->hasOne(Category::class, 'category_id', 'category_id')->with('desc');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id')->withTrashed();
    }

    public function answers(){
        return $this->hasMany(DoubtAnswer::class, 'doubt_id', 'doubt_id');
    }

    public function my_answers(){
        $user = Auth::guard('api')->user();
        $objMyAnswers = $this->hasMany(DoubtAnswer::class, 'doubt_id', 'doubt_id');
        if ($user) {
            $objMyAnswers->where('user_id', $user->user_id)->orderBy('created_at', 'DESC');
        }

        return $objMyAnswers;
    }

    public function getIsSavedAttribute()
    {
        $is_saved_doubt = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $saved_doubt = $this->hasOne(UserSavedItem::class, 'item_type_id', 'doubt_id')->where('item_type', 'DOUBT')->where('user_id', $user->user_id)->first();

            if ($saved_doubt) {
                if ((int)$saved_doubt->item_id > 0) {
                    $is_saved_doubt = true;
                }
            }
        }

        return $is_saved_doubt;
    }

    public function getIsUpvotedAttribute()
    {
        $is_doubt_upvoted = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $upvoted_doubt = $this->hasOne(UserSavedItem::class, 'item_type_id', 'doubt_id')->where('item_type', 'DOUBT_UPVOTE')->where('user_id', $user->user_id)->first();

            if ($upvoted_doubt) {
                if ((int)$upvoted_doubt->item_id > 0) {
                    $is_doubt_upvoted = true;
                }
            }
        }

        return $is_doubt_upvoted;
    }
}

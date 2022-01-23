<?php

namespace App\Models;

use App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Question extends Model
{
	protected $primaryKey = 'questions_id';
	protected $appends = ['is_saved'];

	public function question_desc()
	{
		$language = App::getLocale();
		return $this->hasOne(QuestionDesc::class, 'questions_id', 'questions_id')->where('lang_code', $language);
	}

	public function question_both_lang()
	{
		return $this->hasMany(QuestionDesc::class, 'questions_id', 'questions_id');
	}

	public function category_desc()
	{
		$language = App::getLocale();
		return $this->hasOne(CategoriesDesc::class, 'category_id', 'category_id')->where('lang_code', $language);
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id');
	}

	public function options()
	{
		return $this->hasMany(QuestionOption::class, 'questions_id', 'questions_id')->with(['desc', 'media'])->orderBy('option_order');
	}

	public function question_media()
	{
		$language = App::getLocale();
		return $this->hasMany(QuestionMedia::class, 'media_int_id', 'questions_id')->where('media_int_type', 'QUESTION')->where('lang_code', $language);
	}

	public function solution_media()
	{
		$language = App::getLocale();
		return $this->hasMany(QuestionMedia::class, 'media_int_id', 'questions_id')->where('media_int_type', 'SOLUTION')->where('lang_code', $language);
	}

	public function getIsSavedAttribute()
    {
        $is_saved_quest = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $saved_quest = $this->hasOne(UserSavedItem::class, 'item_type_id', 'questions_id')->where('item_type', 'QUESTION')->where('user_id', $user->user_id)->first();

            if ($saved_quest) {
                if ((int)$saved_quest->item_id > 0) {
                    $is_saved_quest = true;
                }
            }
        }

        return $is_saved_quest;
    }
}

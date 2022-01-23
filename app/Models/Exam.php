<?php

namespace App\Models;

use App;
use Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserAttempt;

class Exam extends Model
{
	protected $primaryKey = "exam_id";
	protected $dates = ['exam_starts_on', 'exam_ends_on', 'result_date'];
	protected $appends = ['prev_attempted', 'prev_completed_attempt'];

	public function desc()
	{
		$language = App::getLocale();
		return $this->hasOne(ExamDesc::class, 'exam_id', 'exam_id')->where('lang_code', $language);
	}

	public function desc_both_lang()
	{
		return $this->hasMany(ExamDesc::class, 'exam_id', 'exam_id');
	}

	public function category_desc()
	{
		$language = App::getLocale();
		return $this->hasOne(CategoriesDesc::class, 'category_id', 'category_id')->where('lang_code', $language);
	}

	public function category()
	{
		return $this->hasOne(Category::class, 'category_id', 'category_id');
	}

	public function getPrevAttemptedAttribute()
	{
		$user = Auth::guard('api')->user();

		if ($user) {
			$objUserAttempt = UserAttempt::query()->where('exam_id', $this->exam_id);

			$objUserAttempt->where('user_id', $user->user_id);
			$objUserAttempt->orderBy('user_attempt_id', 'DESC');

			$user_exam_attempted = $objUserAttempt->first();

			return $user_exam_attempted;
		}

		return null;
	}

	public function getPrevCompletedAttemptAttribute()
	{
		$user = Auth::guard('api')->user();

		if ($user) {
			$objUserAttempt = UserAttempt::query()->where('exam_id', $this->exam_id);
			
			$objUserAttempt->where('user_id', $user->user_id);
			$objUserAttempt->where('attempt_status', 'COMPLETED');
			$objUserAttempt->orderBy('user_attempt_id', 'DESC');

			$user_exam_attempted = $objUserAttempt->first();

			return $user_exam_attempted;
		}

		return null;
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class UserAttempt extends Model
{
	protected $primaryKey = "user_attempt_id";
	protected $fillable = ['user_rank'];

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
	}

	public function exam()
	{
		return $this->belongsTo('App\Models\Exam', 'exam_id', 'exam_id');
	}

	public function exam_desc()
	{
		$language = App::getLocale();
		return $this->belongsTo('App\Models\ExamDesc', 'exam_id', 'exam_id')->where('lang_code', $language);
	}

	public function responses()
	{
		return $this->hasMany(UserExamResponse::class, 'user_attempt_id', 'user_attempt_id');
	}
}

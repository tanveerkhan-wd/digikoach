<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $primaryKey = 'question_options_id';

    public function desc()
    {
        $language = App::getLocale();
        return $this->hasOne(QuestionOptionDesc::class, 'question_options_id', 'question_options_id')->where('lang_code', $language);
    }

    public function question()
    {
        return $this->hasOne(Question::class, 'questions_id', 'questions_id');
    }

    public function question_option_desc()
    {
        return $this->hasMany(QuestionOptionDesc::class, 'question_options_id', 'question_options_id');
    }

    public function media()
    {
        $language = App::getLocale();
        return $this->hasMany(QuestionMedia::class, 'media_int_id', 'question_options_id')->where('media_int_type', 'OPTION')->where('lang_code', $language);
    }
}

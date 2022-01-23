<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $primaryKey = "exam_questions_id";
    
    public function exam()
    {
        return $this->hasOne(Exam::class, 'exam_id', 'exam_id');
    }

    public function question()
    {
        return $this->hasOne(Question::class, 'questions_id', 'questions_id')->with(['options', 'question_desc','category_desc']);
    }
}

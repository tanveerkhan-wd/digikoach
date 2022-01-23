<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionDesc extends Model
{
    protected $primaryKey = 'question_descs_id';

    public function question()
    {
        $this->belongsTo(Question::class, 'questions_id', 'questions_id');
    }
}

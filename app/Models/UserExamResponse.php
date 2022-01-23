<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExamResponse extends Model
{
    protected $primaryKey = 'exam_responses_id';

    public function question(){
        return $this->belongsTo(Question::class, 'questions_id', 'questions_id');
    }
}

<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;

class ExamChallenge extends Model
{
    protected $primaryKey = "exam_challenge_id";
    protected $appends = ['participant'];

    public function users()
    {
        return $this->hasMany(ExamChallengeUser::class, 'exam_challenge_id', 'exam_challenge_id');
    }

    public function getParticipantAttribute()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            return $this->hasOne(ExamChallengeUser::class, 'exam_challenge_id', 'exam_challenge_id')->where('user_id', $user->user_id)->first();
        }

        return null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamChallengeUser extends Model
{
    protected $primaryKey = "challenge_user_id";

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoubtAnswer extends Model
{
    protected $primaryKey = 'answer_id';

    public function doubt(){
        return $this->hasOne(Doubt::class, 'doubt_id', 'doubt_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id')->withTrashed();
    }
}

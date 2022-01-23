<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoubtReply extends Model
{
    protected $primaryKey = 'reply_id';

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id')->withTrashed();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailMaster extends Model
{
  	protected $table = "email_masters";
  	protected $primaryKey = "email_master_id";
	protected $fillable = array('email_master_id','title','parameter','subject','content');    
}

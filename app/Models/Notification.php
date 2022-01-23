<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Notification extends Model
{
	protected $primaryKey = 'notification_id';

	public function desc()
	{
		$language = App::getLocale();
		return $this->hasOne(NotificationDesc::class, 'notification_id', 'notification_id')->where('lang_code', $language);
	}
}

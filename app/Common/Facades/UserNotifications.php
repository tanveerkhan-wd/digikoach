<?php
namespace App\Common\Facades;
use Illuminate\Support\Facades\Facade;

class UserNotifications extends Facade {
	protected static function getFacadeAccessor() {
		return 'usernotifications';
	}
}
<?php
namespace App\Common\Facades;
use Illuminate\Support\Facades\Facade;

class Utilities extends Facade {
	protected static function getFacadeAccessor() {
		return 'utilities';
	}
}
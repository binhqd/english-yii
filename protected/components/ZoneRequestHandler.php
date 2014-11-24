<?php
class ZoneRequestHandler {
	const COOKIE_NAME = 'zone-timezone';
	public static function setup() {
		$cookies = Yii::app()->request->cookies;
		
		// Nếu cookie language tồn tại, lấy thông tin locale
		$timezoneName = date_default_timezone_get();
		if (isset($cookies[self::COOKIE_NAME])) {
			$timezoneName = $cookies[self::COOKIE_NAME]->value;
		}
		Yii::app()->params['timezone'] = $timezoneName;
	}
}
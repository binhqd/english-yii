<?php
class GNAuth extends CComponent {
	const AUTH_BASIC = 1;
	const AUTH_DIGEST = 2;
	protected function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}
	
	public static function unauthorized($type = self::AUTH_BASIC, $message = "Authentication required") {
		$realm = "testrealm@host.com";
		header('HTTP/1.1 401 Unauthorized');
		
		if ($type == self::AUTH_DIGEST) {
			header('WWW-Authenticate: Digest realm="'.$realm.
			'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
		} else if ($type == self::AUTH_BASIC) {
			header('WWW-Authenticate: Basic realm="'.Yii::app()->params['Auth']['API']['realm'].'"');
		}
		
		$out = array(
			'status'	=> 401,
			'error'		=> true,
			'message'	=> $message
		);
		ajaxOut($out);
	}
	
	public static function getAccessToken() {
		$sessID = Yii::app()->session->sessionID;
		return $sessID;
	}
}
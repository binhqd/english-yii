<?php

/**
 * API ZoneUser model
 *
 * @author TienVV
 * @version 1.0
 */
class ApiAccessToken {

	public static $duration = '+3 day';
	public static $prefix = '../tokens/t_';

	const TOKEN_KEY = 'API_ACCESS_TOKEN';

	public static function expiresAt() {
		$duration = static::$duration;
		if (!is_numeric($duration)) {
			$duration = strtotime($duration) - time();
		}
		return intval(time() + $duration);
	}

	public static function requestToken() {
		if (!empty($_GET['cookie-less']) || !isset($_SERVER['HTTP_ACCESS_TOKEN'])) {
			return null;
		}
		return $_SERVER['HTTP_ACCESS_TOKEN'];
	}

	public static function clearCurrentToken() {
		$token = Yii::app()->session[self::TOKEN_KEY];
		if (!$token) {
			$token = static::requestToken();
		}
		if ($token) {
			$cachePath = ZoneBaseConnection::cachePath();
			@unlink($cachePath . static::$prefix . $token);
		}
	}

	public static function validateCode($User, $token) {
		return md5($User->password . '.'
				. $User->saltkey . '.' . $token);
	}

	public static function generate() {
		$User = ZoneUser::model()->findByPk(currentUser()->id);
		if (!$User) {
			throw new Exception(UsersModule::t('Can not generate token because you are not authorized.'), 401);
		}
		static::clearCurrentToken();

		$expiresAt = static::expiresAt();
		//watch(date('Y-m-d H:i:s', $expiresAt));
		$data = array(
			'data' => session_id(),
			'expires_at' => $expiresAt,
			'user_id' => IDHelper::uuidFromBinary($User->id, true)
		);
		//$salt = md5(serialize($data)) . IDHelper::uuid(false);
		$token = preg_replace('/[^a-z0-9]/i', '', base64_encode(md5(serialize($data))));
		$token .= IDHelper::uuid(false);
		$data['code'] = static::validateCode($User, $token);
		return static::_setTokenData($token, $data);
	}

	public static function startSession($token) {
		$data = ZoneBaseConnection::cache(static::$prefix
						. $token, null, static::expiresAt());
		//watch(date('Y-m-d H:i:s') . ' ------> ' . $token);
		if (!$data) {
			throw new Exception(UsersModule::t('Error validating access token: token is not found or expired.'), 190);
		}
		$Session = Yii::app()->session;
		if ($Session->sessionID) {
			throw new Exception(UsersModule::t('Could not validating access token because it has been started.'), 500);
		}
		$Session->sessionID = $data['data'];
		$Session->autoStart = false;
		$Session->open();

		if (!isset(Yii::app()->user->model)) {
			$User = ZoneUser::model()->findByPk(IDHelper::uuidToBinary($data['user_id']));
			if (static::validateCode($User, $token) == @$data['code']) {
				$User->forceLogin();
				static::_setTokenData($token, $data);
			}
		}
		if (currentUser()->isGuest) {
			throw new Exception(UsersModule::t('Error validating access token: The session is invalid because the user logged out.'), 190);
		}
	}

	protected static function _setTokenData($token, $data) {
		$data['data'] = session_id();
		$data['token'] = $token;

		ZoneBaseConnection::cache(static::$prefix
				. $token, $data, $data['expires_at']);
		Yii::app()->session[self::TOKEN_KEY] = $token;
		unset($data['data'], $data['code']);
		return $data;
	}

}

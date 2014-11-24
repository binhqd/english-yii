<?php
class GNAuthBasic extends GNAuth implements IGNAuth {
	public static function parse($txt) {
		// protect against missing data
		$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
		$data = array();
		$keys = implode('|', array_keys($needed_parts));
	
		preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
	
		foreach ($matches as $m) {
			$data[$m[1]] = $m[3] ? $m[3] : $m[4];
			unset($needed_parts[$m[1]]);
		}
	
		return $needed_parts ? false : $data;
	}
	
	public static function checkAuth($email, $password) {
		Yii::import('greennet.modules.users.models.GNLoginForm');
		$model = new GNLoginForm();
		$model->email = $email;
		$model->password = $password;
		try {
			try {
				$model->validate();
			} catch (Exception $ex) {
				GNAuth::unauthorized(GNAuth::AUTH_BASIC, $ex->getMessage());
			}
				
			$login = $model->user->forceLogin(false);
			return $login;
		} catch (Exception $ex) {
			GNAuth::unauthorized(GNAuth::AUTH_BASIC);
		}
	}
}
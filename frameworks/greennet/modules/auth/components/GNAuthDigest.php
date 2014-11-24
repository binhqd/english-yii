<?php 
class GNAuthDigest extends GNAuth implements IGNAuth {
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
	
	public static function checkAuth($strAuth) {
		$realm = "testrealm@host.com";
		
		$data = self::parse($strAuth);
		
		$data['uri'] = '/api';
		
		if (!isset($data['username'])) {
			GNAuth::unauthorized();
		}
		$user = GNUser::model()->find('email=:username', array(
			':username'	=> $data['username']
		));
		if (empty($user)) {
			GNAuth::unauthorized(Yii::t("greennet", "This user doesn't exist in our database"));
		}
		
		$A1 = md5($data['username'] . ':' . $realm . ':' . '123456');
		
		$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
		$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
		
		dump(array(
			$valid_response,
			$data['response']
		));
	}
}
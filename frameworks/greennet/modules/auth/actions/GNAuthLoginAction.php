<?php
Yii::import('greennet.modules.auth.components.*');
class GNAuthLoginAction extends GNAction {
	public function run() {
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			if (GNAuthBasic::checkAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
				$token = GNAuthBasic::getAccessToken();
				
				$out = array(
					'access_token'	=> $token
				);
				ajaxOut($out);
			} else {
				GNAuth::unauthorized(GNAuth::AUTH_BASIC);
			}
		} else {
			GNAuth::unauthorized(GNAuth::AUTH_BASIC);
		}
	}
}
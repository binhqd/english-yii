<?php
Yii::import('greennet.modules.auth.components.*');
class GNApiController extends CController {
	public function init() {
// 		if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
// 			GNAuthDigest::checkAuth($_SERVER['PHP_AUTH_DIGEST']);
// 		}
		
// 		if (empty($_SERVER['PHP_AUTH_DIGEST']) || empty($_REQUEST['access_token'])) {
// 			GNAuth::unauthorized();
// 		}
		
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			if (GNAuthBasic::checkAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
				$token = GNAuthBasic::getAccessToken();
			} else {
				GNAuth::unauthorized(GNAuth::AUTH_BASIC);
			}
		}
		
// 		if (isset($_REQUEST['access_token'])) {
			
// 		}
		
		
		
		//$accessToken = $_REQUEST['access_token'];
	}
	public function actionFilters() {
		return array(
			
// 			'rights',
		);
	}
	
	public function actionIndex() {
		ajaxOut(currentUser()->attributes);
	}
}
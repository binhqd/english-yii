<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiFacebookAccessToken extends GNAction {

	protected $_connector = null;

	/**
	 * This method is used to initialize Facebook connector
	 *
	 * @see GNController::init()
	 */
	public function run($logged = false, $redirectTo = '') {
		$config = CMap::mergeArray(array(
					'class' => 'greennet.modules.social.components.Facebook.GNFacebookConnector'
						), Yii::app()->params['OAuth']['Facebook']);
		$this->_connector = Yii::createComponent($config);
		$this->_connector->init();
		$isConnected = $this->_connector->isConnected;
		if ($logged && !$isConnected) {
			if ($redirectTo) {
				$url = strtr($redirectTo, array(
					'{id}' => '',
					'{token}' => ''
				));
				Yii::app()->getRequest()->redirect($url, true, 302);
			}
			throw new Exception($_GET['error_message']);
		}
		if (!$isConnected) {
			$this->_connector->getAccessToken();
			$queryString = http_build_query(array(
				'redirectTo' => $redirectTo,
				'logged' => 'TRUE'), null, '&');

			$params = array(
				'redirect_uri' => GNRouter::createAbsoluteUrl('/api/users/facebookAccessToken?' . $queryString),
				'scope' => 'email,user_birthday'
			);
			$facebookAuthUrl = $this->_connector->getAuthUrl($params);
			Yii::app()->getRequest()->redirect($facebookAuthUrl, true, 302);
		} else {
			$token = $this->_connector->getAccessToken();
			$user = $this->_connector->userInfo;

			session_destroy();
			if ($redirectTo) {
				$url = strtr($redirectTo, array(
					'{id}' => $user['id'],
					'{token}' => $token
				));
				Yii::app()->getRequest()->redirect($url, true, 302);
			}

			$this->controller->out(200, array(
				'id' => $user['id'],
				'token' => $token
			));
		}
	}

}
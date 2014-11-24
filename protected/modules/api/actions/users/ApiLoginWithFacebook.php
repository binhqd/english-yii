<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiLoginWithFacebook extends GNAction {

	protected $_connector = null;

	/**
	 * This method is used to initialize Facebook connector
	 *
	 * @see GNController::init()
	 */
	public function run($logged = false) {
		watch($_GET);
		$config = CMap::mergeArray(array(
					'class' => 'greennet.modules.social.components.Facebook.GNFacebookConnector'
						), Yii::app()->params['OAuth']['Facebook']);
		$this->_connector = Yii::createComponent($config);
		$this->_connector->init();
		$isConnected = $this->_connector->isConnected;
		if ($logged && !$isConnected) {
			throw new Exception($_GET['error_message']);
		}
		if (!$isConnected) {
			$this->_connector->getAccessToken();
			$params = array(
				'redirect_uri' => GNRouter::createAbsoluteUrl('/api/users/loginWithFacebook?logged=TRUE'),
				'scope' => 'email,user_birthday'
			);
			$facebookAuthUrl = $this->_connector->getAuthUrl($params);
			Yii::app()->getRequest()->redirect($facebookAuthUrl, true, 302);
		} else {
			$token = $this->_connector->getAccessToken();
			$user = $this->_connector->userInfo;
			$this->controller->initAction('connectFacebook')->run($user['id'], $token);
		}
	}

}
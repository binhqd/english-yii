<?php

Yii::import('api_app.components.*');

/**
 * Login action
 * @author huytbt <huytbt@gmail.com>
 * @version 2.0
 */
class APILoginAction extends CAction
{
	/**
	 * Run action
	 */
	public function run()
	{
		ApiAccess::allow("POST");

		if (empty($_POST)) {
			$message = Yii::t("Youlook", 'Invalid Request');
			throw new Exception($message, 400);
		}

		$model = new ZoneLoginForm();
		$model->attributes = array(
			'email' => @$_POST['username'],
			'password' => @$_POST['password']
		);
		if (!$model->validate()) { // Validate form login
			$message = Yii::t("Youlook", 'Your email or password does not match.');
			throw new Exception($message, 401);
		}

		$model->user->forceLogin();
		$currentUser = currentUser();
		// create access token
		$accessToken = ApiAccess::generate();

		$userInfo = ZoneApiResourceFormat::formatData('user', currentUser()->toArray(true));
		$userInfo['stats'] = currentUser()->stats;
		$out = array(
			'user'			=> $userInfo,
			'access_token'	=> $accessToken,
		);
		Yii::app()->response->send(200, $out, "Login successful");
	}
}
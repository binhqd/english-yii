<?php

Yii::import('api_app.components.*');

/**
 * Login action
 * @author huytbt <huytbt@gmail.com>
 * @version 2.0
 */
class APIFacebookLoginAction extends CAction
{
	/**
	 * Run action
	 */
	public function run()
	{
		ApiAccess::allow("POST");

		$fbId = Yii::app()->request->getPost('fbid');
		$fbToken = Yii::app()->request->getPost('fb-token');

		if (empty($fbId) || empty($fbToken)) {
			throw new Exception(null, 400);
		}

		Yii::import('application.modules.users.models.forms.APIFacebookLoginForm');
		$facebookLoginForm = new APIFacebookLoginForm();
		$facebookLoginForm->fbId = $fbId;
		$facebookLoginForm->fbToken = $fbToken;
		if (!$facebookLoginForm->validate()) {
			$errors = array_shift(array_values($facebookLoginForm->errors));
			if (isset($errors[0]))
				throw new Exception($errors[0], 400);
		}

		$fbUser = $facebookLoginForm->userInfo;
		$user = ZoneUser::model()->findByEmail($fbUser['email']);
		if (empty($user)) {
			Yii::import('application.modules.users.models.APIZoneUser');
			$user = APIZoneUser::model()->createUserFromFacebook($fbUser);
		}

		$user->forceLogin();
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
<?php

Yii::import('api_app.components.*');

/**
 * Login action
 * @author huytbt <huytbt@gmail.com>
 * @version 2.0
 */
class APILogoutAction extends CAction
{
	/**
	 * Run action
	 */
	public function run()
	{
		ApiAccess::allow("POST");

		if (currentUser()->isGuest) {
			throw new Exception(null, 403);
		}

		// logout
		Yii::app()->user->logout();
		// clear token
		ApiAccess::clearCurrentToken();

		Yii::app()->response->send(200, array(), "Logout successful");
	}
}
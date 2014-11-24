<?php

/**
 * Auth controller
 * @author huytbt <huytbt@gmail.com>
 * @version 2.0
 */
class AuthController extends GNApiController
{
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	/**
	 * Define actions for controller
	 * @return array
	 */
	public function actions()
	{
		return array(
			'login'	=> array(
				'class'	=> 'api_app.modules.auth.actions.APILoginAction',
			),
			'facebook-login'	=> array(
				'class'	=> 'api_app.modules.auth.actions.APIFacebookLoginAction',
			),
			'logout'	=> array(
				'class'	=> 'api_app.modules.auth.actions.APILogoutAction',
			),
		);
	}
}
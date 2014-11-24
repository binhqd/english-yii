<?php
/**
 * UsersModule - This module is used for userbase
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 31-Jan-2013 10:17:35 AM
 * @modified 31-Jan-2013 10:28:02 AM
 */
class UsersModule extends GNWebModule
{

	/**
	 * Time expiry for activation code
	 */
	public $intExpiryDate = 1;
	/**
	 * Time expire for forgot password
	 */
	public $intExpiryDateForgotPassword = 2;

	/**
	 * Initial module
	 */
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'users.models.*',
			'users.components.*',
		));
	}

	/**
	 * beforeControllerAction
	 * @param $controller
	 * @param $action
	 */
	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
<?php

class Validation_codesModule extends CWebModule
{
	public $defaultController = 'default';
	
	/**
	 * Define variable for expried codes
	 * @var unknown_type
	 */
	public $intExpiryDate = 1;
	public $intExpiryDate_Forgot_Password = 2;
	public $intExpiryDate_Invite_Friend = 2;
	public $intExpiryDate_Active_Email = 2;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'validation_codes.models.*',
			'validation_codes.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
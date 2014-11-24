<?php

/**
 * Module dùng cho việc quản lý user
 * 
 * @class	   UserModule
 * @author	  huytbt
 * @version	 1.0
 * @date		2011-05-23
 */
class InteractionsModule extends JLWebModule
{
	/**
	 * Thiết lập Controller mặc định cho Module
	 */
	public $defaultController = 'Dashboard';
	
	
	/**
	 * init
	 */
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'interactions.models.*',
			'interactions.components.*'
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
